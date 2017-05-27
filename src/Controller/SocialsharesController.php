<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Socialshares Controller
 *
 * @property \App\Model\Table\SocialsharesTable $Socialshares
 */
class SocialsharesController extends AppController
{
    
    /**
     * all method
     *
     * @return void
     */
    public function schedule(){
        $query = $this->Socialshares->find()->where(['client_id' => $this->_appSession->read('App.selectedClienteID'),'status' => 0])->orderDesc('schedule_date'); 
        $this->set('socialshares', $query);
        $this->set('_serialize', ['socialshares']);
    }
    public function all(){
        $query = $this->Socialshares->find()->where(['client_id' => $this->_appSession->read('App.selectedClienteID')]); 
        $this->set('socialshares', $query);
        $this->set('_serialize', ['socialshares']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
//        $this->paginate = [
//            'contain' => ['Clients']
//        ];
//        $socialshares = $this->paginate($this->Socialshares);
//        
        $result = [
            'facebook' => [
                'connected' => 0
            ],
            'twitter' => [
                'connected' => 0
            ]
        ];
        
        
        $sconnTbl = \Cake\ORM\TableRegistry::get('SocialConnections');
        $connections = $sconnTbl->find()->where([
            'SocialConnections.client_id' => $this->_appSession->read('App.selectedClienteID')
        ]);
        $atkn = [];
        foreach ($connections as $con){
            $result[$con->model]['connected'] = 1;
//            $result[$con->model]['access_token'] = $con->access_token;
            $atkn[$con->model]['access_token'] = $con->access_token;
        }
        if($result['facebook']['connected'] == 1){
            $data = file_get_contents('https://graph.facebook.com/me/accounts?access_token='.$atkn['facebook']['access_token']);
            $ownID = file_get_contents('https://graph.facebook.com/me?access_token='.$atkn['facebook']['access_token']);
            $result['facebook']['self'] = json_decode($ownID);
            $result['facebook']['pages'] = json_decode($data);
        }

        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }

    /**
     * View method
     *
     * @param string|null $id Socialshare id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $socialshare = $this->Socialshares->get($id, [
            'contain' => ['Clients']
        ]);

        $this->set('socialshare', $socialshare);
        $this->set('_serialize', ['socialshare']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $d = $this->request->data;
        $socialshare = $this->Socialshares->newEntity();
        if ($this->request->is('post')) {
            $result = [
                'error' => 1,
                'msg' => 'some thing went wrong please try again'
            ];
            $d['status'] = 1;
            $d['client_id'] = $this->Auth->user('id');
            $socialshare = $this->Socialshares->patchEntity($socialshare, $d);
            if ($this->Socialshares->save($socialshare)) {
                $result = [
                    'error' => 0,
                    'msg' => 'Social Share Schedule Scuccessfully'
                ];
            }
        }
        $clients = $this->Socialshares->Clients->find('list', ['limit' => 200]);
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Socialshare id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
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
        $clients = $this->Socialshares->Clients->find('list', ['limit' => 200]);
        $this->set(compact('socialshare', 'clients'));
        $this->set('_serialize', ['socialshare']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Socialshare id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
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
