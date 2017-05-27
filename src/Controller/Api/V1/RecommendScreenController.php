<?php
namespace App\Controller\Api\V1;

use App\Controller\Api\V1\AppController;

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
        \Cake\Core\Configure::write('debug', FALSE);
        $d = $this->request->data;
//        $d['store_id'] =1;
//        $d['customer_id'] =1;
//        $d['recommended'] = '{"final":[{"number":"7838283001","name":"karan"},{"number":"9646631704","name":"dharam"}]}';
        
        $refered = json_decode($d['recommended'],TRUE);
        $cstTable = \Cake\ORM\TableRegistry::get('Customers');
        $messageTbl = \Cake\ORM\TableRegistry::get('Messages');
        $ex = [];
        $c = 0;
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
                        ->where(['id' => $this->request->header('client-id')])
                        ->first();
                if($clientInfo['sms_quantity']>0){
                    $methods = new \App\Common\Methods();
                    $apiResonpse = ['apiResponse' => ""];
                    if($num = $methods->checkNum($rf['number'])){
                        if($clientInfo['senderid']){
                            $apiResonpse = $methods->smsgshup(
                                $num,
                                $newmessage,
                                $this->request->header('client-id'),
                                $clientInfo['senderid'],
                                "Recommend SMSes (FRIEND)",
                                @$saveCustomer->id,
                                $d['store_id']
                            );
                        }else{
                            $apiResonpse = $methods->smsgshup(
                                $num,
                                $newmessage,
                                $this->request->header('client-id'),
                                "ZAKOPI",
                                "Recommend SMSes (FRIEND)",
                                @$saveCustomer->id,
                                $d['store_id']
                            );
                        }
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
        
        
        $str_id = $this->request->header('store-id');
        $settingsTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $rscd = $settingsTbl->find()->where([
            'store_id' => $str_id,
            'meta_key' => 'r-screen-customer-discount',
            'meta_value' => '1'
        ])->first();
        
        if($rscd){
            $rscdm = $settingsTbl->find()->where([
                'store_id' => $str_id,
                'meta_key' => 'r-screen-customer-discount-msg'
            ])->first();
            $newmessage = $rscdm->meta_value;
            $newmessage = str_replace('{{cstName}}', explode(" ", $cstNm['name'])[0], $newmessage);
            $newmessage = str_replace('{{storeName}}', $campaigns->store->name, $newmessage);
            $checkEmbaded = $this->RecommendScreen->find()->where(['store_id' => $campaigns->store->id])->first();
            if($checkEmbaded){
                $promo = $methods->getpromo();
                $newmessage = $newmessage." CPN-".$promo;
            }
            if($clientInfo['sms_quantity']>2){
                $methods = new \App\Common\Methods();
                $apiResonpse = [];
                if($num = $methods->checkNum($cstNm['contact_no'])){
                    if($clientInfo['senderid']){
                        $apiResonpse = $methods->smsgshup(
                            $num,
                            $newmessage,
                            $this->request->header('client-id'),
                            $clientInfo['senderid'],
                            "Recommend SMSes (PROMOTER)",
                            $cstNm['id'],
                            $campaigns->store->id
//                            $clientInfo['plivo_auth_id'],
//                            $clientInfo['plivo_auth_token']
                        );
                    }else{
                        $apiResonpse = $methods->smsgshup(
                            $num,
                            $newmessage,
                                $this->request->header('client-id'),
                            "ZAKOPI",
                            "Recommend SMSes (PROMOTER)",
                            $cstNm['id'],
                            $campaigns->store->id
                        );
                    }
                }
                $apijsonResonpse = (json_encode($apiResonpse));
                file_put_contents(WWW_ROOT.'rq.txt', print_r($cstNm, true).$apijsonResonpse);
            }
        }
        
        
        
//        file_put_contents(WWW_ROOT.'rq.txt', print_r($d, true));
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
}
