<?php
namespace App\Controller\Api\V1;

use App\Controller\Api\V1\AppController;

/**
 * Socialshares Controller
 *
 * @property \App\Model\Table\SocialsharesTable $Socialshares
 */
class SocialsharesController extends AppController
{

    
    /**
     * @apiDescription SocialShare
     * @apiUrl http://www.business.zakoopi.com/api/socialshares/savecode.json
     * @apiRequestType POST method
     * @apiRequestData {"client_id":ClientID,"customer_id":Customer ID,"code":Code,"type":Type,"store_id":Store ID}
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"result": "0/1"}}
     */
    
    public function savecode(){
        $result = 0;
        $d = $this->request->data;
        
        $tblShrdCodes = \Cake\ORM\TableRegistry::get('Sharedcodes');
        $ent = $tblShrdCodes->newEntity([
           'client_id' => $d['client_id'],
           'customer_id' => $d['customer_id'],
           'code' => $d['code'],
           'type' => $d['type'],
           'store_id' => $d['store_id'],
           'redeemed_count' => 0,
           'created_at' => time()
        ]);
        $s = $tblShrdCodes->save($ent);
        if($s){
            $methods = new \App\Common\Methods();
            $nCode = $methods->getpromo();
            $refererBenifit = $tblShrdCodes->newEntity([
                'client_id' => $d['client_id'],
                'customer_id' => $d['customer_id'],
                'code' =>   $nCode,
                'type' => $d['type'].'-promoter',
                'store_id' => $d['store_id'],
                'redeemed_count' => 0,
                'created_at' => time()
            ]);
            
            $clientTbl = \Cake\ORM\TableRegistry::get("Clients");
            $client = $clientTbl->get($d['client_id']);
            
            
            $str_id = $d['store_id'];
            $settingsTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
            $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
            $cstName = $cstTbl->get($d['customer_id']);
            $strtTbl = \Cake\ORM\TableRegistry::get('Stores');
            $strName = $strtTbl->get($str_id);
            $rscd = $settingsTbl->find()->where([
                'store_id' => $str_id,
                'meta_key' => 's-screen-customer-discount',
                'meta_value' => '1'
            ])->first();
            if($rscd){
                $rscdm = $settingsTbl->find()->where([
                    'store_id' => $str_id,
                    'meta_key' => 's-screen-customer-discount-msg'
                ])->first();
                $newmessage = $rscdm->meta_value;
                $newmessage = str_replace('{{cstName}}', explode(" ", $cstName->name)[0], $newmessage);
                $newmessage = str_replace('{{storeName}}', $strName->name, $newmessage);
                if($client->sms_quantity > 2){
                    $methods = new \App\Common\Methods();
                    if($num = $methods->checkNum($cstName->contact_no)){
                        $apiResonpse = $methods->smsgshup(
                            $num,
                            $newmessage,
                            $d['client_id'],
                            $client->senderid,
                            "Social Media SMS (".strtoupper($d['type'])."-PROMOTER)",
                            $cstName->id,
                            $str_id
                        );
                        $apijsonResonpse = (json_encode($apiResonpse));
                    }
                }
            }
            
            $result = 1;
        }else{
            $result = 0;
        }
        file_put_contents('rq.txt', print_r($ent,true));
        $this->set('result', $result);
        $this->set('_serialize', ['result']);
    }
    
    /**
     * @apiDescription Socialshare
     * @apiUrl http://www.business.zakoopi.com/api/socialshares/savecode2.json
     * @apiRequestType POST method
     * @apiRequestData {"client_id":ClientID,"customer_id":Customer ID,"code":Code,"type":Type,"store_id":Store ID}
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"result": "0/1"}}
     */
    public function savecode2(){
        $result = 0;
        $d = $this->request->data;
        
        $tblShrdCodes = \Cake\ORM\TableRegistry::get('Sharedcodes');
        $ent = $tblShrdCodes->newEntity([
           'client_id' => $d['client_id'],
           'customer_id' => $d['customer_id'],
           'code' => $d['code'],
           'type' => $d['type'],
           'store_id' => $d['store_id'],
           'redeemed_count' => 0,
           'created_at' => time()
        ]);
        $s = $tblShrdCodes->save($ent);
        if($s){
            $result = 1;
            $methods = new \App\Common\Methods();
            $nCode = $methods->getpromo();
            $refererBenifit = $tblShrdCodes->newEntity([
                'client_id' => $d['client_id'],
                'customer_id' => $d['customer_id'],
                'code' =>   $nCode,
                'type' => $d['type'].'-promoter',
                'store_id' => $d['store_id'],
                'redeemed_count' => 0,
                'created_at' => time()
            ]);
            $cstTbl = \Cake\ORM\TableRegistry::get("Customers");
            $cst = $cstTbl->get($d['client_id']);
            
            $clientTbl = \Cake\ORM\TableRegistry::get("Clients");
            $client = $clientTbl->get($d['client_id']);
            
            if($tblShrdCodes->save($refererBenifit))
            {
                $newmessage = "Dear {{cstName}}, Thank you for promoting us. We wish to extend you a 5% discount for your next visit. Studio De Royale";
                $newmessage = str_replace('{{cstName}}', @$cst->name, $newmessage);
                if($num = $methods->checkNum($cst->contact_no)){
                    $methods->smsgshup($num, $newmessage, @$d['client_id'], @$client->senderid,"Social Media SMS (".strtoupper($d['type'])."-PROMOTER)",$d['customer_id'],$d['store_id'],"PromoToReferer");
                }
            }
        }else{
            $result = 0;
        }
        file_put_contents('rq.txt', print_r($ent,true));
        $this->set('result', $result);
        $this->set('_serialize', ['result']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('socialshares', $this->paginate($this->Socialshares));
        $this->set('_serialize', ['socialshares']);
    }

    /**
     * View method
     *
     * @param string|null $id Socialshare id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $socialshare = $this->Socialshares->get($id, [
            'contain' => []
        ]);
        $this->set('socialshare', $socialshare);
        $this->set('_serialize', ['socialshare']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $socialshare = $this->Socialshares->newEntity();
        if ($this->request->is('post')) {
            $socialshare = $this->Socialshares->patchEntity($socialshare, $this->request->data);
            if ($this->Socialshares->save($socialshare)) {
                $this->Flash->success(__('The socialshare has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The socialshare could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('socialshare'));
        $this->set('_serialize', ['socialshare']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Socialshare id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $socialshare = $this->Socialshares->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $socialshare = $this->Socialshares->patchEntity($socialshare, $this->request->data);
            if ($this->Socialshares->save($socialshare)) {
                $this->Flash->success(__('The socialshare has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The socialshare could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('socialshare'));
        $this->set('_serialize', ['socialshare']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Socialshare id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $socialshare = $this->Socialshares->get($id);
        if ($this->Socialshares->delete($socialshare)) {
            $this->Flash->success(__('The socialshare has been deleted.'));
        } else {
            $this->Flash->error(__('The socialshare could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
