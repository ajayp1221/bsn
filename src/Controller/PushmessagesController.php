<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Pushmessages Controller
 *
 * @property \App\Model\Table\PushmessagesTable $Pushmessages
 */
class PushmessagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if($limit = $this->request->query('limit')){
            
        }  else {
            $limit = 1;
        }
        $qry = $this->Pushmessages->find()->where(['store_id'=>$this->_appSession->read('App.selectedStoreID')]);
        $count = $qry->count();
        $pushmessages = $this->paginate($qry);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);

        $this->set(compact('pushmessages','pager'));
        $this->set('_serialize', ['pushmessages','pager']);
    }

    /**
     * View method
     *
     * @param string|null $id Pushmessage id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pushmessage = $this->Pushmessages->get($id, [
            'contain' => ['Stores']
        ]);

        $this->set('pushmessage', $pushmessage);
        $this->set('_serialize', ['pushmessage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pushmessage = $this->Pushmessages->newEntity();
        if ($this->request->is('post')) {
            $d = $this->request->data;
            $d['status'] = 1;
            $storeInfo = $this->Pushmessages->Stores->find()->select(['id','slug'])->hydrate(FALSE)
                    ->where(['id' => $this->_appSession->read('App.selectedStoreID')])->first();
            $d['store_slug'] = $storeInfo['slug'];
            $d['at_time'] = explode(":", $this->request->data['at_time'])[0].":00";
            $d['store_id'] = $this->_appSession->read('App.selectedStoreID');
            $pushmessage = $this->Pushmessages->patchEntity($pushmessage, $d);
            if ($this->Pushmessages->save($pushmessage)) {
                $result = [
                    'error'=>0,
                    'msg' => 'Push Notification has been added successfully And will send on schedule time'
                ];
            } else {
                $result = [
                    'error'=>1,
                    'msg' => 'Something went wrong please try again!!!'
                ];
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Pushmessage id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pushmessage = $this->Pushmessages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pushmessage = $this->Pushmessages->patchEntity($pushmessage, $this->request->data);
            if ($this->Pushmessages->save($pushmessage)) {
                $result =[
                    'error' => 0,
                    'msg' => 'Updated successfully'
                ];
            } else {
                $result =[
                    'error' => 1,
                    'msg' => 'Something went wrong please try again'
                ];
            }
        }
        $stores = $this->Pushmessages->Stores->find('list', ['limit' => 200]);
        $this->set(compact('pushmessage', 'stores','result'));
        $this->set('_serialize', ['pushmessage','result']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Pushmessage id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pushmessage = $this->Pushmessages->get($id);
        if ($this->Pushmessages->delete($pushmessage)) {
            $result =[
                'error' => 0,
                'msg' => 'deleted successfully'
            ];
        } else {
            $result =[
                'error' => 1,
                'msg' => 'Something went wrong please try again'
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }
    
    /**
     * status method
     *
     * @return void
     */
    public function changeStatus() {
        $this->viewBuilder()->layout('ajax');
        $this->request->allowMethod(['post', 'put']);
        $res = $this->Pushmessages->updateAll(
                ['status' => $this->request->data['status']], ['id' => $this->request->data['id']]);
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
