<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Messages Controller
 *
 * @property \App\Model\Table\MessagesTable $Messages
 * @property \App\Model\Table\ClientsTable $Clients
 */
class MessagesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Customers', 'Stores', 'Campaigns']
        ];
        $this->set('messages', $this->paginate($this->Messages));
        $this->set('_serialize', ['messages']);
    }

    /**
     * View method
     *
     * @param string|null $id Message id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
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
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $message = $this->Messages->newEntity();
        if ($this->request->is('post')) {
            $message = $this->Messages->patchEntity($message, $this->request->data);
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('The message has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The message could not be saved. Please, try again.'));
            }
        }
        $customers = $this->Messages->Customers->find('list', ['limit' => 200]);
        $stores = $this->Messages->Stores->find('list', ['limit' => 200]);
        $campaigns = $this->Messages->Campaigns->find('list', ['limit' => 200]);
        $this->set(compact('message', 'customers', 'stores', 'campaigns'));
        $this->set('_serialize', ['message']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Message id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $message = $this->Messages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $message = $this->Messages->patchEntity($message, $this->request->data);
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('The message has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The message could not be saved. Please, try again.'));
            }
        }
        $customers = $this->Messages->Customers->find('list', ['limit' => 200]);
        $stores = $this->Messages->Stores->find('list', ['limit' => 200]);
        $campaigns = $this->Messages->Campaigns->find('list', ['limit' => 200]);
        $this->set(compact('message', 'customers', 'stores', 'campaigns'));
        $this->set('_serialize', ['message']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Message id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $softDeleted =  $this->Messages->updateAll(['soft_deleted' => 1], ['id' => $id]);
        if ($softDeleted) {
            $this->Flash->success(__('The message has been deleted.'));
        } else {
            $this->Flash->error(__('The message could not be deleted. Please, try again.'));
        }
        return $this->redirect($this->referer());
    }
    
    public function send(){
        \Cake\Core\Configure::write('debug',false);
        if($this->request->is(['post','put'])){
            $methods = new \App\Common\Methods();
            $d = $this->request->data;
            if($d['c']){
                foreach($d['c'] as $contactNo){
                    if($num1 = $methods->checkNum($contactNo)){
                        $checkMobileNo1 = preg_match("^[789]\d{9}$^", $num1);
                        if($checkMobileNo1){
                            $apiResonpse = $methods->smsgshup($num1, $d['message']);
                        }
                    }
                }
            }
            if($d['numbers']){
                $receivers = explode(",", $d['numbers']);
                foreach ($receivers as $receiver) {
                    if($num = $methods->checkNum($receiver)){
                        $checkMobileNo = preg_match("^[789]\d{9}$^", $num);
                        if($checkMobileNo){
                            $apiResonpse = $methods->smsgshup($num, $d['message']);
                        }
                    }
                }
            }
            $this->Flash->success('Sms sent successfully');
            return $this->redirect($this->referer());
        }
        $this->loadModel('Clients');
        $clients = $this->Clients->find()->select(['contact_no','name'])->toArray();
        $this->set(compact('clients'));
    }
}
