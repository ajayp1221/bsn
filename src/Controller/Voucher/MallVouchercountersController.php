<?php
namespace App\Controller\Voucher;

use App\Controller\Voucher\AppController;

/**
 * MallVouchercounters Controller
 *
 * @property \App\Model\Table\MallVouchercountersTable $MallVouchercounters
 */
class MallVouchercountersController extends AppController
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
        $this->paginate = [
            'contain' => ['Stores']
        ];
        $mallVouchercounters = $this->paginate($this->MallVouchercounters);

        $this->set(compact('mallVouchercounters'));
        $this->set('_serialize', ['mallVouchercounters']);
    }

    /**
     * View method
     *
     * @param string|null $id Mall Vouchercounter id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $mallVouchercounter = $this->MallVouchercounters->get($id, [
            'contain' => ['Stores']
        ]);

        $this->set('mallVouchercounter', $mallVouchercounter);
        $this->set('_serialize', ['mallVouchercounter']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mallVouchercounter = $this->MallVouchercounters->newEntity();
        if ($this->request->is('post')) {
            $mallVouchercounter = $this->MallVouchercounters->patchEntity($mallVouchercounter, $this->request->data);
            if ($this->MallVouchercounters->save($mallVouchercounter)) {
                $this->Flash->success(__('The mall vouchercounter has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The mall vouchercounter could not be saved. Please, try again.'));
            }
        }
        $stores = $this->MallVouchercounters->Stores->find('list', ['limit' => 200]);
        $this->set(compact('mallVouchercounter', 'stores'));
        $this->set('_serialize', ['mallVouchercounter']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Mall Vouchercounter id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mallVouchercounter = $this->MallVouchercounters->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mallVouchercounter = $this->MallVouchercounters->patchEntity($mallVouchercounter, $this->request->data);
            if ($this->MallVouchercounters->save($mallVouchercounter)) {
                $this->Flash->success(__('The mall vouchercounter has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The mall vouchercounter could not be saved. Please, try again.'));
            }
        }
        $stores = $this->MallVouchercounters->Stores->find('list', ['limit' => 200]);
        $this->set(compact('mallVouchercounter', 'stores'));
        $this->set('_serialize', ['mallVouchercounter']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Mall Vouchercounter id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mallVouchercounter = $this->MallVouchercounters->get($id);
        if ($this->MallVouchercounters->delete($mallVouchercounter)) {
            $this->Flash->success(__('The mall vouchercounter has been deleted.'));
        } else {
            $this->Flash->error(__('The mall vouchercounter could not be deleted. Please, try again.'));
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
                return $this->redirect(['controller' => 'MallVouchercounters', 'action' => 'index']);
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
                    return $this->redirect(['controller' => 'MallVouchercounters', 'action' => 'index']);
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
}
