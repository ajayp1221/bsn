<?php
namespace App\Controller\Api\V1;

use App\Controller\Api\V1\AppController;

/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class ProductsController extends AppController
{

    /**
     * @apiDescription Products
     * @apiUrl http://www.business.zakoopi.com/api/prodcuts/index/(store_id).json
     * @apiRequestType POST method
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"products": "<entity>"}}
     */
    public function index($store_id = null)
    {
        $this->paginate = [
//            'contain' => ['Stores']
        ];
        $x = $this->paginate($this->Products->find()->where([
            'Products.store_id' => $store_id
        ]));
        $data = (new \Cake\Collection\Collection($x))->groupBy('category');
        if(count($data->toArray()) > 0){
            $this->set('products', ['count' => count($data->toArray()),'data'=>$data]);
        }else{
            $this->set('products', ['count' => count($data->toArray())]);
        }
        
        $strSettingsTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $settingPrdScreen = $strSettingsTbl->find()->where([
            'StoreSettings.store_id' => $store_id,
            'StoreSettings.meta_key' => 'productScreen'
        ])->first();
        $showScreen = 1;
        if($settingPrdScreen){
            $showScreen = (int) $settingPrdScreen->meta_value;
        }
        if($x->count() == 0){
            $showScreen = 0;
        }
        $this->set('showScreen', $showScreen);
        $this->set('_serialize', ['products','showScreen']);
    }
    
    /**
     * @apiDescription Products
     * @apiUrl http://www.business.zakoopi.com/api/prodcuts/index2/(store_id).json
     * @apiRequestType POST method
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"products": "<entity>"}}
     */
    public function index2($store_id = null)
    {
        $catTbl = \Cake\ORM\TableRegistry::get('Productcats');
        $x = $catTbl->find()->contain(['Products'])->where([
		'Productcats.store_id' => $store_id
	    ])->all()->toArray();
        $this->paginate = [
//            'contain' => ['Stores']
        ];
        $rdata = [];
        foreach ($x as $r){
            if(count($r->products) != 0){
                $rdata[] = $r;
            }
        }
        $data = (new \Cake\Collection\Collection($rdata))->groupBy('gender');

        if(count($data) > 0){
            $this->set('products', ['count' => count($data),'data'=>$data]);
        }else{
            $this->set('products', ['count' => count($data)]);
        }
        $strSettingsTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $settingPrdScreen = $strSettingsTbl->find()->where([
            'StoreSettings.store_id' => $store_id,
            'StoreSettings.meta_key' => 'productScreen'
        ])->first();
        $showScreen = 1;
        if($settingPrdScreen){
            $showScreen = (int) $settingPrdScreen->meta_value;
        }
        if(count($x) == 0){
            $showScreen = 0;
        }
        $this->set('showScreen', $showScreen);
        $this->set('_serialize', ['products','showScreen']);
    }

    /**
     * View method
     *
     * @param string|null $id Product id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => ['Stores']
        ]);
        $this->set('product', $product);
        $this->set('_serialize', ['product']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $product = $this->Products->newEntity();
        if ($this->request->is('post')) {
            $product = $this->Products->patchEntity($product, $this->request->data);
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The product could not be saved. Please, try again.'));
            }
        }
        $stores = $this->Products->Stores->find('list', ['limit' => 200]);
        $this->set(compact('product', 'stores'));
        $this->set('_serialize', ['product']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $product = $this->Products->patchEntity($product, $this->request->data);
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The product could not be saved. Please, try again.'));
            }
        }
        $stores = $this->Products->Stores->find('list', ['limit' => 200]);
        $this->set(compact('product', 'stores'));
        $this->set('_serialize', ['product']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Product id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $product = $this->Products->get($id);
        if ($this->Products->delete($product)) {
            $this->Flash->success(__('The product has been deleted.'));
        } else {
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
