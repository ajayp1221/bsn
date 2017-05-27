<?php
namespace App\Controller;

use App\Controller\AppController;

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

        $limit = 10;
        if(@$this->request->query('limit')){
            $limit = $this->request->query('limit');
        }
        $q = $this->MallVouchercounters->find()->where(['store_id' => $this->_appSession->read('App.selectedStoreID')]);
        $count = $q->count();
        $mallVouchercounters = $this->paginate($q);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        
        $this->set(compact('mallVouchercounters','pager'));
        $this->set('_serialize', ['mallVouchercounters','pager']);
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
            $result = [
                'error' => 1,
                'msg' => 'something went wrong please try again'
            ];
            $this->request->data['pwd'] = $this->request->data['password'];
            $this->request->data['store_id'] = $this->_appSession->read('App.selectedStoreID');
            $this->request->data['status'] = 1;
            $mallVouchercounter = $this->MallVouchercounters->patchEntity($mallVouchercounter, $this->request->data);
            if ($this->MallVouchercounters->save($mallVouchercounter)) {
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
            $result = [
                'error' => 1,
                'msg' => 'something went wrong please try again'
            ];
            $mallVouchercounter = $this->MallVouchercounters->patchEntity($mallVouchercounter, $this->request->data);
            if ($this->MallVouchercounters->save($mallVouchercounter)) {
                $result = [
                    'error' => 0,
                    'msg' => 'Updated Successfully'
                ];
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
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
            $result = [
                'error' => 1,
                'msg' => 'Deleted successfully'
            ];
        } else {
            $result = [
                'error' => 1,
                'msg' => 'something went wrong please try again'
            ];
        }
        return $this->redirect(['action' => 'index']);
    }
    
    public function changeStatus() {
        $this->request->allowMethod(['post', 'put']);
        $res = $this->MallVouchercounters->updateAll(
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
