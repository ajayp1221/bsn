<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Stores Controller
 *
 * @property \App\Model\Table\StoresTable $Stores
 */
class StoresController extends AppController
{

    
    
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Brands']
        ];
        $stores = $this->paginate($this->Stores);

        $this->set(compact('stores'));
        $this->set('_serialize', ['stores']);
    }

    /**
     * View method
     *
     * @param string|null $id Store id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $store = $this->Stores->get($id, [
            'contain' => ['Brands', 'Albums', 'Campaigns', 'CustomerVisits', 'Customers', 'Messages', 'Productcats', 'Products', 'Purchases', 'Pushmessages', 'Questions', 'RecommendScreen', 'ShareScreen', 'Sharedcodes', 'Templatemessages', 'Welcomemsgs']
        ]);

        $this->set('store', $store);
        $this->set('_serialize', ['store']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $store = $this->Stores->newEntity();
        if ($this->request->is('post')) {
            $store = $this->Stores->patchEntity($store, $this->request->data);
            if ($this->Stores->save($store)) {
                $this->Flash->success(__('The store has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The store could not be saved. Please, try again.'));
            }
        }
        $brands = $this->Stores->Brands->find('list', ['limit' => 200]);
        $this->set(compact('store', 'brands'));
        $this->set('_serialize', ['store']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Store id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $store = $this->Stores->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $store = $this->Stores->patchEntity($store, $this->request->data);
            if ($this->Stores->save($store)) {
                $this->Flash->success(__('The store has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The store could not be saved. Please, try again.'));
            }
        }
        $brands = $this->Stores->Brands->find('list', ['limit' => 200]);
        $this->set(compact('store', 'brands'));
        $this->set('_serialize', ['store']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Store id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $store = $this->Stores->get($id);
        if ($this->Stores->delete($store)) {
            $this->Flash->success(__('The store has been deleted.'));
        } else {
            $this->Flash->error(__('The store could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    public function getMyStores(){
        $result = $this->Stores->find()->contain(['Brands'])->where(['Brands.client_id'=>  $this->Auth->user('id')])->all();
        $this->set('result',$result);
        $this->set('_serialize', ['result']);
    }
    
    public function setStore($id = null){
        $this->request->allowMethod(['post']);
        if($id){
            $this->_appSession->write('App.selectedStoreID',$id);
            $result = [
                'error' => 0,
                'msg' => "Selected new Store..."
            ];
        }else{
            $result = [
                'error' => 1,
                'msg' => "Missing StoreID..."
            ];
        }
        $this->set('result',$result);
        $this->set('_serialize', ['result']);
    }
    
    /**
     * API to provide current store
     */
    public function getSelectedStore(){
        $data = $this->Stores->find()->contain([
            'Brands' => function($q){
                return $q->select(['id','client_id','brand_name'])->contain([
                    'Clients' => function($q){
                        return $q->select(['name','contact_no']);
                    }
                ]);
            }
        ])->select([
            'id','brand_id','name','is_customer_feedback','is_appdata','is_analytics','is_mall'
        ])->where([
            'Stores.id' => $this->_appSession->read('App.selectedStoreID')
        ])->first();
        $this->set('data',$data);
        $this->set('_serialize',['data']);
    }
    
    
}
