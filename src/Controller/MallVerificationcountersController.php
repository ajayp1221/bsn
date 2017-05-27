<?php
namespace App\Controller;

use App\Controller\AppController;

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
        $limit = 10;
        if(@$this->request->query('limit')){
            $limit = $this->request->query('limit');
        }
        $q = $this->MallVerificationcounters->find()->where(['store_id' => $this->_appSession->read('App.selectedStoreID')]);
        $count = $q->count();
        $mallVerificationcounters = $this->paginate($q);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        
        $this->set(compact('mallVerificationcounters','pager'));
        $this->set('_serialize', ['mallVerificationcounters','pager']);
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
            $this->request->data['pwd'] = $this->request->data['password'];
            $this->request->data['store_id'] = $this->_appSession->read('App.selectedStoreID');
            $this->request->data['status'] = 1;
            $result = [
                'error' => 1,
                'msg' => 'something went wrong please try again'
            ];
            $mallVerificationcounter = $this->MallVerificationcounters->patchEntity($mallVerificationcounter, $this->request->data);
            if ($this->MallVerificationcounters->save($mallVerificationcounter)) {
                $result = [
                    'error' => 0,
                    'msg' => 'Saved Successfully'
                ];
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
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
    
    
    public function changeStatus() {
        $this->request->allowMethod(['post', 'put']);
        $res = $this->MallVerificationcounters->updateAll(
        [
            'status'=>$this->request->data['status']
        ],[
            'id' => $this->request->data['id']
        ]);
        
        if($res){
            $response['error'] = 0;
            $response['msg'] = "Status has been change scuccessfully.";
        }else {
            $response['error'] = 1;
            $response['msg'] = "Something went wrong please try again";
        }
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
    
}
