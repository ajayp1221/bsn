<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 * @property \App\Model\Table\MessagesTable $Messages
 */

class ClientsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $date = date('Y/m/d H:i');
        $this->loadModel('Messages');
        if(!empty($this->request->query['q'])){
            $searchKeyword = str_replace(' ', '%', $this->request->query['q']);
            $qry = $this->Clients->find()->where([
                'OR' => [
                    'name LIKE' => "%".$searchKeyword."%",
                    'email LIKE' => "%".$searchKeyword."%",
                    'contact_no LIKE' => "%".$searchKeyword."%"
                ]
            ])->orderDesc('id');
        }else{
            $qry = $this->Clients->find()->orderDesc('id');
        }
        $clients = $this->paginate($qry);
        /*---------------Need Total Message----------------*/
        
        $messages = $this->Messages->find()->where(['Messages.status' => 1])->join([
            'stores' => [
                'table' => 'stores',
                'type' => 'inner',
                'conditions' => 'stores.id = Messages.store_id and stores.status = 1'
            ],
            'campaigns' => [
                'table' => 'campaigns',
                'type' => 'inner',
                'conditions' => "campaigns.id = Messages.campaign_id and campaigns.status = 1 and campaigns.send_date >= '$date'"
            ]
        ])->count();
        
        $query = $this->Clients->find()->where([
            'Clients.status' => 1
        ]);
        
        /*---------------max count message schedule by client ----------------*/
        
        $maxclientcmsneed = $this->Messages->find()->contain([
            'Stores'=> function($q){
                return $q->select(['id','brand_id'])->contain([
                    'Brands' => function($q){
                        return $q->select(['id','client_id'])->contain([
                            'Clients' => function($q){
                                return $q->select(['id','name']);
                            }
                        ]);
                    }
                ]);
            }
        ])->matching('Campaigns', function($q){
            return $q->where(['send_date >=' => date("Y/m/d H:00"),'Campaigns.status' => 1]);
        })->select(["customersCount" => $this->Messages->find()->func()->count("Messages.id")])
        ->group('Messages.store_id')->orderDesc('customersCount')->first();
        
        $sum = $query->select(['creditsNeeded' => $query->func()->sum('sms_quantity')])->execute()->fetch('assoc')['creditsNeeded'];
        $this->set(compact('clients', 'messages','sum','maxclientcmsneed'));
        $this->set('_serialize', ['clients']);
    }

    /**
     * View method
     *
     * @param string|null $id Client id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $client = $this->Clients->get($id, [
            'contain' => ['Smsplans', 'Brands']
        ]);
        $this->set('client', $client);
        $this->set('_serialize', ['client']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->viewBuilder()->layout('form');
        $client = $this->Clients->newEntity();
        if ($this->request->is('post')) {
            $checkEmail = $this->Clients->find()->where(['email' => $this->request->data['email']])->count();
            if($checkEmail){
                $this->Flash->success(__('Email is already exits. Please try with differnt email id'));
            }else{
                $rand = "ZKP@".rand(100,1000);
                $this->request->data['password'] = $rand;
                $this->request->data['pwd'] = $rand;
                $this->request->data['status'] = 1;
                $client = $this->Clients->patchEntity($client, $this->request->data);
                if ($this->Clients->save($client)) {
                    $methods = new \App\Common\Methods();
                    $methods->sendnewemail($this->request->data['email'],"Username: ".$this->request->data['email']." , Password: ".$this->request->data['password']);
                    $this->Flash->success(__('The client has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The client could not be saved. Please, try again.'));
                }
            }
        }
        $smsplans = $this->Clients->Smsplans->find('list', ['limit' => 200]);
        $this->set(compact('client', 'smsplans'));
        $this->set('_serialize', ['client']);
    }

    /**
     * AddPlan method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function addplan($clientid = NULL)
    {
        $this->viewBuilder()->layout('form');
        $client = $this->Clients->newEntity();
        if ($this->request->is('post')) {
            if($this->request->data['credit_sms']){
                $sms = $this->request->data['credit_sms'];
                $email = 0;
            }else{
                $this->loadModel('ClientsSmsplans');
                $this->request->data['created'] = time();
                $clientssmsplans = $this->ClientsSmsplans->newEntity();
                $clientssmsplans = $this->ClientsSmsplans->patchEntity($clientssmsplans, $this->request->data);
                $smsPlan = $this->Clients->Smsplans->find()->select(['sms','email'])->hydrate(false)->where(['id' => $this->request->data['smsplan_id']])->first();
                $sms = $smsPlan['sms'];
                $email = $smsPlan['email'];
                $saveRecord = $this->ClientsSmsplans->save($clientssmsplans);
            }
            $res = $this->Clients->updateAll([
                        "sms_quantity = sms_quantity + $sms",
                        "email_left = email_left + $email",
                        "low_balance_sms_pause" => 0,
                        "low_balance_email_pause" => 0,
                        "low_balance2_sms_pause" => 0,
                        "low_balance2_email_pause" => 0
                    ],[
                        'id' => $this->request->data['client_id'
                            ]
                    ]);
            $smslTbl = \Cake\ORM\TableRegistry::get('Smsledger');
            $smslTbl->addCredit($sms,$this->request->data['client_id'],"SMS Credit Added from ADMIN DASHBOARD",0,0, 'Added by- ' . $this->Auth->user('email'));
            if ($res) {
                $updatedCl = $this->Clients->get($this->request->data['client_id']);
                $smsMsg = "Hi! Your account has been recharged with " . $sms . " SMS & " . $email . " Email credits. Balance SMS and Email credits are " . $updatedCl->sms_quantity . " & " . $updatedCl->email_left . ", respectively - Team Zakoopi";
                $methods = new \App\Common\Methods();
                $methods->smsgshup($updatedCl->contact_no, $smsMsg);
                $this->Flash->success(__('Plan added successfully'));
                return $this->redirect($this->referer());
            } else {
                $this->Flash->error(__('Somethimg went wrong please try again.'));
            }
        }
        $smsplans = $this->Clients->Smsplans->find('list', ['limit' => 200]);
        $this->set(compact('client', 'smsplans', 'clientid'));
        $this->set('_serialize', ['client']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Client id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->layout('form');
        $client = $this->Clients->get($id, [
            'contain' => ['Smsplans']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $client = $this->Clients->patchEntity($client, $this->request->data);
            if ($this->Clients->save($client)) {
                $this->Flash->success(__('The client has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The client could not be saved. Please, try again.'));
            }
        }
        $smsplans = $this->Clients->Smsplans->find('list', ['limit' => 200]);
        $this->set(compact('client', 'smsplans'));
        $this->set('_serialize', ['client']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Client id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $update = $this->Clients->updateAll(['soft_deleted' => 1], ['id' => $id]);
        if ($update) {
            $this->Flash->success(__('The client has been deleted.'));
        } else {
            $this->Flash->error(__('The client could not be deleted. Please, try again.'));
        }
        return $this->redirect($this->referer());
    }
    
    public function check(){
        \Cake\Core\Configure::write('debug',FALSE);
        $starttimestamp = strtotime($this->request->query['st']);
        $endtimestamp   = strtotime($this->request->query['et']);
        if(empty($this->request->query['st'] || $this->request->query['et'])){
            $starttimestamp = strtotime("01-01-2015");
            $endtimestamp   = strtotime(date("d-m-Y"));
        }

        $this->loadModel('Customers');
        $customers = $this->Customers->find('list',['keyField' => 'id','valueField' => 'id'])->where([
            'created >= ' => $starttimestamp,
            'created <= ' => $endtimestamp,
            function($q){
                return $q->notin('contact_no',[
                        '7838283001', '9646631704', '8860641616','8960799090','8750784618','9717179320','9311528180','9718829114',
                        '7572072098'
                    ]);
            }
        ])->toArray();

        $this->loadModel('Answers');
        $answers = $this->Answers->find()->where([
            'created >= ' => $starttimestamp,
            'created <= ' => $endtimestamp,
            'customers_id IN' => $customers
        ])->count();

        $clients = $this->Clients->find('list',['valueField' => 'id'])->where([
            function($q){
                return $q->notin('contact_no', [
                    '7838283001', '9646631704', '8860641616','8960799090','8750784618','9717179320','9311528180','9718829114',
                    '7572072098'
                ]);
            }
        ])->toArray();

        $this->loadModel('Socialshares');
        $socialshares = $this->Socialshares->find()->where([
            'created >= ' => $starttimestamp,
            'created <= ' => $endtimestamp,
            'client_id IN' => $clients
        ])->count();

        $this->loadModel('Messages');
        $messages = $this->Messages->find()->where([
            'created >= ' => $starttimestamp,
            'created <= ' => $endtimestamp,
            'used' => 1,
            'customer_id IN' => $customers
        ])->count();

        $customers = count($customers);
        $this->set(compact(['answers', 'customers', 'socialshares','messages']));
        $this->set('_serialize', ['answers', 'customers', 'socialshares','messages']);
    }
    
    public function update(){
        $result = $this->Clients->updateAll(['max_device_limit' => $this->request->data['max_device_limit']],['id' => $this->request->data['id']]);
        if($result){
            $response = 0;
        }else{
            $response = 1;
        }
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}
