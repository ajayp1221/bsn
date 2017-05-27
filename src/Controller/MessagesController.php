<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Messages Controller
 *
 * @property \App\Model\Table\MessagesTable $Messages
 * @property \App\Model\Table\SharedcodesTable $Sharedcodes
 * @property \App\Model\Table\CustomersTable $CustomersSharedcodeRedeemed
 * @property \App\Model\Table\SharedcodeRedeemedTable $SharedcodeRedeemed
 */
class MessagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        
        $limit = $this->request->query['limit'];
        $qry = $this->Messages->find()
                ->contain([
                    'Customers' => function($q){
                        return $q->select(['name','id','contact_no']);
                    },
                    'Campaigns' => function($q){
                        return $q->select(['id','compaign_name','message']);
                    }
                ])
                ->select(['id','promo_code','used','used_date','status','created'])
                ->where([
                    'Messages.promo_code <>'=>'','Messages.store_id' => $this->_appSession->read('App.selectedStoreID'),
                    'Messages.used' => 1
                ])->limit($limit);
        $count = $qry->count();
        $messages = $this->paginate($qry);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        $this->set(compact('messages','pager'));
        $this->set('_serialize', ['messages','pager']);
    }

    /**
     * View method
     *
     * @param string|null $id Message id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $message = $this->Messages->get($id, [
            'contain' => ['Customers', 'Stores', 'Campaigns']
        ]);

        $this->set('message', $message);
        $this->set('_serialize', ['message']);
    }
    /**
     * Delete method
     *
     * @param string|null $id Message id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $message = $this->Messages->get($id);
        if ($this->Messages->delete($message)) {
            $this->Flash->success(__('The message has been deleted.'));
        } else {
            $this->Flash->error(__('The message could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    /**
     * For Redeemed Coupon Code
     * @return string
     */
    
    
    public function verifyPromo(){
        $this->request->is(['post','put']);
        $this->loadModel('Sharedcodes');
        if($this->request->data['promo_code'] && empty($this->request->data['contact_no'])){
            $checkPromo = $this->Messages->find()
                    ->hydrate(false)
                    ->where([
                        'promo_code' => $this->request->data['promo_code'],
                        'store_id' => $this->_appSession->read('App.selectedStoreID')
                    ])
                    ->first();
            if($checkPromo){
                if($checkPromo['used']){
                    $result = [
                        'error' => 1,
                        'msg' => 'Entered promo code has been already used'
                    ];
                }else{
                    $this->Messages->updateAll(['used' => 1,'used_date' => time()], ['id' => $checkPromo['id']]);
                    $result = [
                        'error' => 0,
                        'msg' => 'Promo Code has been redeemed successfully'
                    ];
                }
            }else{
                $checkSharedCode = $this->Sharedcodes->find()
                        ->hydrate(false)
                        ->where([
                            'store_id' => $this->_appSession->read('App.selectedStoreID'),
                            'code'=>$this->request->data['promo_code']
                        ])->first();
                if($checkSharedCode){
                    $result = [
                        'error' => 2,
                        'msg' => 'Code verified'
                    ];
                }else{
                    $result = [
                        'error' => 1,
                        'msg' => 'Coupon Code does not exit!!!'
                    ];
                }
            }
        }elseif(@$this->request->data['contact_no']){
            $sharedcodesid = $this->Sharedcodes->find()->select([
                'id','type','redeemed_count'
            ])->hydrate(false)->where([
                'code'=>$this->request->data['promo_code']
            ])->first();
            if(($sharedcodesid['type']=="facebook-promoter" || $sharedcodesid['type']=="twitter-promoter"|| $sharedcodesid['type']=="recommended-offer") && $sharedcodesid['redeemed_count']>0){
                $result = [
                    'error' => 1,
                    'msg' => 'Coupon code has been already redeemed!!!'
                ];
            }else{
                $this->Sharedcodes->updateAll(['redeemed_count = redeemed_count + 1'], ['id'=>$sharedcodesid['id']]);
                $customeId = $this->Messages->Customers->find()
                        ->select(['id'])
                        ->hydrate(false)
                        ->where(['contact_no'=>$this->request->data['contact_no'],'store_id'=>$this->_appSession->read('App.selectedStoreID')])
                        ->first();
                if($customeId){
                    $customeid = $customeId['id'];
                }else{
                    $this->loadModel('Customers');
                    $customer = $this->Customers->newEntity();
                    $customer->contact_no = $this->request->data['contact_no'];
                    $customer->store_id = $this->_appSession->read('App.selectedStoreID');
                    $customer->status = 1;
                    $result = $this->Customers->save($customer);
                    $customeid = $result->id;
                }
                $this->loadModel('SharedcodeRedeemed');
                $checkExit = $this->SharedcodeRedeemed->find()->where(['sharedcode_id'=>$sharedcodesid['id'],'customer_id'=>$customeid])->first();

                if($checkExit){
                    $result = [
                        'error' => 1,
                        'msg' => 'Promo code is allready used by this contact number'
                    ];
                }else{
                    $sharedcodeRedeemed = $this->SharedcodeRedeemed->newEntity();
                    $sharedcodeRedeemedData =[
                        'customer_id' => $customeid,
                        'redeemed_at' => time(),
                        'sharedcode_id' => $sharedcodesid['id']
                    ];
                    $sharedcodeRedeemed = $this->SharedcodeRedeemed->patchEntity($sharedcodeRedeemed, $sharedcodeRedeemedData);
                    $resultShared = $this->SharedcodeRedeemed->save($sharedcodeRedeemed);
                    $result = [
                        'error' => 0,
                        'msg' => 'Social Promo code has been redeemed successfully'
                    ];
                }
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize',['result']);
    }
}
