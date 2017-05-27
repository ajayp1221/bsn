<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * RecommendScreen Controller
 *
 * @property \App\Model\Table\RecommendScreenTable $RecommendScreen
 */
class RecommendScreenController extends AppController
{

    /**
     * @apiDescription Recommended Friend
     * @apiUrl http://www.business.zakoopi.com/api/recommend-screen/recommended.json
     * @apiRequestType POST method
     * @apiRequestData {"store_id":Store ID, "customer_id":Customer ID, "recommended": E.G - {"final":[{"number":"7838283001","name":"karan"},{"number":"9646631704","name":"dharam"}]}}
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"result":"<entity>"}
     */
    
    public function recommended(){
//        \Cake\Core\Configure::write('debug', FALSE);
        $d = $this->request->data;
//        $d['store_id'] =2;
//        $d['customer_id'] =326;
//        $d['recommended'] = '{"final":[{"number":"7838283001","name":"Ajay"},{"number":"9646631704","name":"Abhineet"}]}';
	file_put_contents(WWW_ROOT.'rq.txt', print_r($d, true));
        $methods = new \App\Common\Methods();
        $refered = json_decode($d['recommended'],TRUE);
        $cstTable = \Cake\ORM\TableRegistry::get('Customers');
        $messageTbl = \Cake\ORM\TableRegistry::get('Messages');
        $ex = [];
        $c = 0;
	$clientId = $this->request->header('client-id');
        //$clientId = 2;
        $campaignsTbl = \Cake\ORM\TableRegistry::get('Campaigns');
        $campaigns = $campaignsTbl->find()->contain(['Stores'])->where(['store_id' => $d['store_id'], 'campaign_type' => 'refered'])->first();
        $cstNm = $cstTable->find()->select(['id', 'name','contact_no'])->hydrate(FALSE)->where(['id' => $d['customer_id']])->first();
        foreach($refered['final'] as $rf){
            $ent = $cstTable->find()->where([
                "Customers.contact_no" => $rf['number'],
                "Customers.store_id" => $d['store_id'],
            ])->first();
            if(!$ent){
                $ent = $cstTable->newEntity([
                    'refered_by' => $d['customer_id'],
                    'name' => $rf['name'],
                    'added_by' => "RECOMMENDED",
                    'contact_no' => $rf['number'],
                    'status' => 1,
                    'soft_deleted' => 0,
                    'store_id' => $d['store_id'],
                    'opt_in' => 0
                ]);
            }else{
                if($ent->name == '')
                    $ent->set('name', $rf['name']);
            }
            $ex[] = $ent;
            if($num = $methods->checkNum($rf['number'])){
                if($campaigns){
                $saveCustomer = $cstTable->save($ent);
                $promo = "";
                $newmessage = $campaigns['message'];
                /*if ($campaigns['embedcode']) {
                    $promo = time() . $c;
                    $c++;
                    $newmessage = $campaigns['message']." CPN- ".$promo;
                }*/
                $city = explode("-", $campaigns->store->slug);
                $city = $city[(count($city) - 1)] == "ncr" ? "delhi-ncr" : $city[(count($city) - 1)];
                $googl = new \Sonrisa\Service\ShortLink\Google('AIzaSyAzC7nmB9Y2shrUBg2749_AYwOpBQhxGlY');
                $strUrl = "http://www.zakoopi.com/".$city."/".$campaigns->store->slug;
                /*try {
                    $strUrl = $googl->shorten($strUrl);
                } catch (Exception $ex) {
                    $strUrl = "";
                }*/
                $newmessage = str_replace('{{cstName}}', explode(" ", $rf['name'])[0], $newmessage);
                $newmessage = str_replace('{{frndName}}', explode(" ", $cstNm['name'])[0], $newmessage);
                $newmessage = str_replace('{{storeName}}', $campaigns->store->name, $newmessage);
                $newmessage = str_replace('{{ZakoopiStoreShortLink}}', $strUrl, $newmessage);
                $this->loadModel('Clients');
                $clientInfo = $this->Clients->find()
                        ->hydrate(false)
                        ->select(['id','sms_quantity','senderid','plivo_auth_id','plivo_auth_token'])
                        ->where(['id' => $clientId])
                        ->first();
                if($clientInfo['sms_quantity']>0){
                    
                        if($clientInfo['senderid']){
                            $apiResonpse = $methods->smsgshup(
                                $num,
                                $newmessage,
                                $clientId,
                                $clientInfo['senderid'],
                                "Recommend SMSes (FRIEND)",
                                @$saveCustomer->id,
                                $d['store_id']
                            );
                        }else{
                            $apiResonpse = $methods->smsgshup(
                                $num,
                                $newmessage,
                                $clientId,
                                "ZAKOPI",
                                "Recommend SMSes (FRIEND)",
                                @$saveCustomer->id,
                                $d['store_id']
                            );
                    }
                    $apijsonResonpse = (json_encode($apiResonpse));
                }
                $externalID = explode("|", $apiResonpse['apiResponse']);
                $externalID = trim($externalID[2]); //'external_msgid' => $externalID
                $data1 = [
                    'customer_id' => $saveCustomer->id,
                    'store_id' => $d['store_id'],
                    'campaign_id' => $campaigns->id,
                    'promo_code' => $promo,
                    'api_key' => $apiResonpse['status'],
                    'api_response' => $apijsonResonpse,
                    'status' => '0',
                    'cost_multiplier' => $apiResonpse['response']['units'],
                    'external_msgid' => $externalID
                ];
                $message = $messageTbl->newEntity();
                $message = $messageTbl->patchEntity($message, $data1);
                $results[] = $messageTbl->save($message);
                unset($message);
            }
            }
        }
        $str_id = $this->request->header('store-id');
//	$str_id = 2;
        $settingsTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $rscd = $settingsTbl->find()->where([
            'store_id' => $str_id,
            'meta_key' => 'r-screen-customer-discount',
            'meta_value' => '1'
        ])->first();
		
        if(!$rscd){
            /**
             * Start SharedCode Store In Database
             */
            $promo = "";
            $promo = $methods->getpromo();
            $rscdm = $settingsTbl->find()->where([
                'store_id' => $str_id,
                'meta_key' => 's-screen-customer-discount-msg'
            ])->first();
            $sharedcodesTbl = \Cake\ORM\TableRegistry::get('Sharedcodes');
            $sharedcodesData = [
                'client_id' => $clientId,
                'customer_id' => $cstNm['id'],
                'code' => $promo,
                'type' => 'recommended-offer',
                'store_id' => $d['store_id'],
                'redeemed_count' => 0
            ];
            $dataSave = $sharedcodesTbl->newEntity($sharedcodesData);
            $result = $sharedcodesTbl->save($dataSave);
            /**
             * END SharedCode Store In Database
             */
            $newmessage = $rscdm->meta_value;
            $newmessage = str_replace('{{cstName}}', explode(" ", $cstNm['name'])[0], $newmessage);
            $newmessage = str_replace('{{storeName}}', $campaigns->store->name, $newmessage);
            $newmessage = $newmessage." CPN- ".$promo;
            if($clientInfo['sms_quantity']>2){
                $apiResonpse = [];
                if($num = $methods->checkNum($cstNm['contact_no'])){
                    if($clientInfo['senderid']){
                        $apiResonpse = $methods->smsgshup(
                            $num,
                            $newmessage,
                            $clientId,
                            $clientInfo['senderid'],
                            "Recommend SMSes (PROMOTER)",
                            $cstNm['id'],
                            $campaigns->store->id
                        );
                    }else{
                        $apiResonpse = $methods->smsgshup(
                            $num,
                            $newmessage,
                            $clientId,
                            "ZAKOPI",
                            "Recommend SMSes (PROMOTER)",
                            $cstNm['id'],
                            $campaigns->store->id
                        );
                    }
                }
                $apijsonResonpse = (json_encode($apiResonpse));
            }
        }
        $this->set('result',1);
        $this->set('_serialize',['result']);
    }
    
    /**
     * Index method
     *
     * @return void
     */
    public function index($store_id = null)
    {
        $this->paginate = [
            'contain' => ['Stores']
        ];
        $this->set('recommendScreen', $this->RecommendScreen->find()->where(['store_id'=>$store_id])->first());
        $this->set('_serialize', ['recommendScreen']);
    }

    /**
     * View method
     *
     * @param string|null $id Recommend Screen id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $recommendScreen = $this->RecommendScreen->get($id, [
            'contain' => ['Stores']
        ]);
        $this->set('recommendScreen', $recommendScreen);
        $this->set('_serialize', ['recommendScreen']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        if($recommendScreen = $this->RecommendScreen->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID')
                ])->first()){
            
        }else{
            $recommendScreen = $this->RecommendScreen->newEntity();
        }
        if ($this->request->is(['post','put'])) {
            $recommendScreen = $this->RecommendScreen->patchEntity($recommendScreen, $this->request->data);
            if ($result = $this->RecommendScreen->save($recommendScreen)) {
                $this->Flash->success(__('The recommend screen has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The recommend screen could not be saved. Please, try again.'));
            }
        }
        $stores = $this->RecommendScreen->Stores->find('list', ['limit' => 200]);
        $this->set(compact('recommendScreen', 'stores'));
        $this->set('_serialize', ['recommendScreen']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Recommend Screen id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $recommendScreen = $this->RecommendScreen->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $recommendScreen = $this->RecommendScreen->patchEntity($recommendScreen, $this->request->data);
            if ($this->RecommendScreen->save($recommendScreen)) {
                $this->Flash->success(__('The recommend screen has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The recommend screen could not be saved. Please, try again.'));
            }
        }
        $stores = $this->RecommendScreen->Stores->find('list', ['limit' => 200]);
        $this->set(compact('recommendScreen', 'stores'));
        $this->set('_serialize', ['recommendScreen']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Recommend Screen id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $recommendScreen = $this->RecommendScreen->get($id);
        if ($this->RecommendScreen->delete($recommendScreen)) {
            $this->Flash->success(__('The recommend screen has been deleted.'));
        } else {
            $this->Flash->error(__('The recommend screen could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
