<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    /**
     * beforeFilter method
     *
     * @return void
     */
    
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'login']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        if(!empty($this->request->query['q'])){
            $searchKeyword = str_replace(' ', '%', $this->request->query['q']);
            $qry = $this->Users->find()->where([
                'OR' => [
                    'first_name LIKE' => "%".$searchKeyword."%",
                    'last_name LIKE' => "%".$searchKeyword."%",
                    'email LIKE' => "%".$searchKeyword."%"
                ]
            ]);
        }else{
            $qry = $this->Users;
        }
        $this->set('users', $this->paginate($qry));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['status'] = 1;
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $softDeleted = $this->Users->updateAll(['soft_deletd' => 1], ['id' => $id]);
        if ($softDeleted) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect($this->referer());
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
                return $this->redirect(['controller' => 'clients', 'action' => 'index']);
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
                    return $this->redirect(['controller' => 'clients', 'action' => 'index']);
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
