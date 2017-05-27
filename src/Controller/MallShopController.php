<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * MallShop Controller
 *
 * @property \App\Model\Table\MallShopTable $MallShop
 */
class MallShopController extends AppController
{

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
        
        $count = $this->MallShop->find()->count();
        $mallShop = $this->paginate($this->MallShop);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        
        $this->set(compact('mallShop','pager'));
        $this->set('_serialize', ['mallShop','pager']);
    }

    /**
     * View method
     *
     * @param string|null $id Mall Shop id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $mallShop = $this->MallShop->get($id, [
            'contain' => []
        ]);

        $this->set('mallShop', $mallShop);
        $this->set('_serialize', ['mallShop']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mallShop = $this->MallShop->newEntity();
        $result = [
            'error' => 1,
            'msg' => 'something went wrong please try again'
        ];
        if ($this->request->is('post')) {
            $this->request->data['status'] = 1;
            $mallShop = $this->MallShop->patchEntity($mallShop, $this->request->data);
            if ($this->MallShop->save($mallShop)) {
                $this->Flash->success(__('The mall shop has been saved.'));
                $result = [
                    'error' => 0,
                    'msg' => 'Shop added successfully',
                ];
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Mall Shop id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mallShop = $this->MallShop->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mallShop = $this->MallShop->patchEntity($mallShop, $this->request->data);
            if ($this->MallShop->save($mallShop)) {
                $this->Flash->success(__('The mall shop has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The mall shop could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('mallShop'));
        $this->set('_serialize', ['mallShop']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Mall Shop id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mallShop = $this->MallShop->get($id);
        if ($this->MallShop->delete($mallShop)) {
            $this->Flash->success(__('The mall shop has been deleted.'));
        } else {
            $this->Flash->error(__('The mall shop could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    public function changeStatus() {
        $this->request->allowMethod(['post', 'put']);
        $res = $this->MallShop->updateAll(
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
