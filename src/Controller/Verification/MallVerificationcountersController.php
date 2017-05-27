<?php
namespace App\Controller\Verification;

use App\Controller\Verification\AppController;

/**
 * MallVerificationcounters Controller
 *
 * @property \App\Model\Table\MallVerificationcountersTable $MallVerificationcounters
 */
class MallVerificationcountersController extends AppController
{

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
//        $this->Auth->allow();
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $tbl = \Cake\ORM\TableRegistry::get("MallCustomerbills");
        $this->paginate = [
            'contain' => ['Customers' => function($q){
                                        return $q->select(['id','name','email','contact_no']);
            },'Stores' => function($q){
                                return $q->select(['id','name','address']);}]
        ];
        $bills = $this->paginate($tbl->find()->where([
            "MallCustomerbills.store_id" => $this->Auth->user('store_id')
        ]))->toArray();
        $this->set(compact('bills'));
        $this->set('_serialize', ['bills']);
    }

    /**
     * View method
     *
     * @param string|null $id Mall Verificationcounter id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $mallVerificationcounter = $this->MallVerificationcounters->get($id, [
            'contain' => ['Stores']
        ]);

        $this->set('mallVerificationcounter', $mallVerificationcounter);
        $this->set('_serialize', ['mallVerificationcounter']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mallVerificationcounter = $this->MallVerificationcounters->newEntity();
        if ($this->request->is('post')) {
            $mallVerificationcounter = $this->MallVerificationcounters->patchEntity($mallVerificationcounter, $this->request->data);
            if ($this->MallVerificationcounters->save($mallVerificationcounter)) {
                $this->Flash->success(__('The mall verificationcounter has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The mall verificationcounter could not be saved. Please, try again.'));
            }
        }
        $stores = $this->MallVerificationcounters->Stores->find('list', ['limit' => 200]);
        $this->set(compact('mallVerificationcounter', 'stores'));
        $this->set('_serialize', ['mallVerificationcounter']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Mall Verificationcounter id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mallVerificationcounter = $this->MallVerificationcounters->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mallVerificationcounter = $this->MallVerificationcounters->patchEntity($mallVerificationcounter, $this->request->data);
            if ($this->MallVerificationcounters->save($mallVerificationcounter)) {
                $this->Flash->success(__('The mall verificationcounter has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The mall verificationcounter could not be saved. Please, try again.'));
            }
        }
        $stores = $this->MallVerificationcounters->Stores->find('list', ['limit' => 200]);
        $this->set(compact('mallVerificationcounter', 'stores'));
        $this->set('_serialize', ['mallVerificationcounter']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Mall Verificationcounter id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mallVerificationcounter = $this->MallVerificationcounters->get($id);
        if ($this->MallVerificationcounters->delete($mallVerificationcounter)) {
            $this->Flash->success(__('The mall verificationcounter has been deleted.'));
        } else {
            $this->Flash->error(__('The mall verificationcounter could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Login method
     *
     * @return void
     */
    public function login() {
        $this->viewBuilder()->layout('login');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                if ($this->request->data['remember'] == "1") {
                    $cookie = array();
                    $cookie['email'] = $this->request->data['email'];
                    $cookie['password'] = $this->request->data['password'];
                    $this->Cookie->write('remember', $cookie, true, "1 week");
                    unset($this->request->data['remember']);
                }
                $this->Flash->error(__('Logged in successfully'));
                return $this->redirect(['controller' => 'MallVerificationcounters', 'action' => 'index']);
//                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Username or password is incorrect'));
            }
        } else {
            if (($this->Cookie->read('remember'))) {
                $this->request->data = $this->Cookie->read('remember');
                $user = $this->Auth->identify();
                if ($user) {
                    $this->Auth->setUser($user);
                    return $this->redirect(['controller' => 'MallVerificationcounters', 'action' => 'index']);
//                    return $this->redirect($this->Auth->redirectUrl());
                }
            }
        }
    }
    
    /**
     * Logout method
     */

    public function logout() {
        $this->Cookie->delete('remember');
        return $this->redirect($this->Auth->logout());
    }
    
    public function verified(){
        $mallCstBilTbl = \Cake\ORM\TableRegistry::get('MallCustomerbills');
        $res = $mallCstBilTbl->updateAll(['status' => 'VERIFIED'], ['id' => $this->request->data['id']]);
        if($res){
            $result = [
                'error' => 0,
                'msg' => 'Updated successfully'
            ];
        }else{
            $result = [
                'error' => 1,
                'msg' => 'something went wrong please try again'
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize',['result']);
    }
    
    public function invalid(){
        $mallCstBilTbl = \Cake\ORM\TableRegistry::get('MallCustomerbills');
        $res = $mallCstBilTbl->updateAll(['status' => 'INVALID'], ['id' => $this->request->data['id']]);
        if($res){
            $result = [
                'error' => 0,
                'msg' => 'Updated successfully'
            ];
        }else{
            $result = [
                'error' => 1,
                'msg' => 'something went wrong please try again'
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize',['result']);
    }
}
