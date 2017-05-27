<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * RecommendScreen Controller
 *
 * @property \App\Model\Table\RecommendScreenTable $RecommendScreen
 */
class RecommendScreenController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $recommendScreen = $this->RecommendScreen->find()->where(['store_id' => $this->_appSession->read('App.selectedStoreID')])->first();
        $settingsTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $rscd = $settingsTbl->find()->where([
            "store_id" => $this->_appSession->read('App.selectedStoreID'),
            "meta_key" => 's-screen-customer-discount'
        ])->first();
        $rscdm = $settingsTbl->find()->where([
            "store_id" => $this->_appSession->read('App.selectedStoreID'),
            "meta_key" => 's-screen-customer-discount-msg'
        ])->first();
        $result = ["a"=>$rscd->toArray(),"b"=>$rscdm->toArray()];
        $this->set(compact('recommendScreen','result'));
        $this->set('_serialize', ['recommendScreen','result']);
    }

    /**
     * View method
     *
     * @param string|null $id Recommend Screen id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $recommendScreen = $this->RecommendScreen->get($id, [
            'contain' => ['Stores']
        ]);

        $this->set('recommendScreen', $recommendScreen);
        $this->set('_serialize', ['recommendScreen']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    { 
        if ($this->request->is(['post','put'])) {
            if($recommendScreen = $this->RecommendScreen->find()->where([
                'store_id' => $this->_appSession->read('App.selectedStoreID')
                    ])->first()){

            }else{
                $recommendScreen = $this->RecommendScreen->newEntity();
            }
            if($this->request->data['active']){
                $this->request->data['active'] = 1;
            }else{
                $this->request->data['active'] = 0;
            }
            if($this->request->data['discountOn']){
                $this->request->data['embedcode'] = 1;
            }else{
                $this->request->data['embedcode'] = 0;
            }
            
            $d = $this->request->data;
            $recommendScreen = $this->RecommendScreen->patchEntity($recommendScreen, $this->request->data);
            if ($result = $this->RecommendScreen->save($recommendScreen)) {
                $settingsTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
                $str_id = $this->_appSession->read('App.selectedStoreID');
                $rscd = $settingsTbl->find()->where([
                    "store_id" => $str_id,
                    "meta_key" => 's-screen-customer-discount'
                ])->first();
                if(!$rscd){
                    $rscd = $settingsTbl->newEntity();
                }

                $rscdm = $settingsTbl->find()->where([
                    "store_id" => $str_id,
                    "meta_key" => 's-screen-customer-discount-msg'
                ])->first();
                if(!$rscdm){
                    $rscdm = $settingsTbl->newEntity();
                }
                if($d['discountOn'] == 0){
                    $data = [
                        "store_id" => $str_id,
                        "meta_key" => "s-screen-customer-discount",
                        "meta_value" => $d['discountOn'],
                    ];
                    $rscd = $settingsTbl->patchEntity($rscd, $data);
                    $settingsTbl->save($rscd);
                }
                if($d['discountOn'] == "1"){
                    $data = [
                        "store_id" => $str_id,
                        "meta_key" => "s-screen-customer-discount-msg",
                        "meta_value" => $d['discountOnText'],
                    ];
                    $rscdm = $settingsTbl->patchEntity($rscdm, $data);
                    $settingsTbl->save($rscdm);
                }
                $result = [
                    'error' => 0,
                    'msg' => 'Saved'
                ];
            } else {
                $result = [
                    'error' => 1,
                    'msg' => 'Something went wrong please try again'
                ];
            }
        }
        $stores = $this->RecommendScreen->Stores->find('list', ['limit' => 200]);
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Recommend Screen id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $recommendScreen = $this->RecommendScreen->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $recommendScreen = $this->RecommendScreen->patchEntity($recommendScreen, $this->request->data);
            if ($this->RecommendScreen->save($recommendScreen)) {
                $this->Flash->success(__('The recommend screen has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The recommend screen could not be saved. Please, try again.'));
            }
        }
        $stores = $this->RecommendScreen->Stores->find('list', ['limit' => 200]);
        $this->set(compact('recommendScreen', 'stores'));
        $this->set('_serialize', ['recommendScreen']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Recommend Screen id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $recommendScreen = $this->RecommendScreen->get($id);
        if ($this->RecommendScreen->delete($recommendScreen)) {
            $this->Flash->success(__('The recommend screen has been deleted.'));
        } else {
            $this->Flash->error(__('The recommend screen could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
