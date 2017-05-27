<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class ProductsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if($this->_appSession->read('App.selectedStoreID') == 16 || $this->_appSession->read('App.selectedStoreID') == 6){
            $catTbl = \Cake\ORM\TableRegistry::get('Productcats');
            $x = $catTbl->find()->contain(['Products'])->where([
		'Productcats.store_id' => $this->_appSession->read('App.selectedStoreID')
	    ])->all();

            $data = (new \Cake\Collection\Collection($x))->groupBy('gender');
            $this->paginate = [
                'limit' => 1000
            ];
            $this->set('products', $data);
            $catTbl = \Cake\ORM\TableRegistry::get('Productcats');
            $categories = $catTbl->find()->where(['store_id'=>$this->_appSession->read('App.selectedStoreID')]);
            $this->set('pcategories', $categories);
        }else{
            $this->paginate = [
                'limit' => 1000
            ];
            $this->set('products', $this->paginate($this->Products->find()->where([
                'Products.store_id' => $this->_appSession->read('App.selectedStoreID')
            ])));
        }
        $this->set('_serialize', ['products']);
    }

    /**
     * View method
     *
     * @param string|null $id Product id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => ['Stores', 'Productcats', 'Purchases']
        ]);

        $this->set('product', $product);
        $this->set('_serialize', ['product']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
//        \Cake\Core\Configure::write('debug',true);
        $this->request->allowMethod(['post','put']);
        $d = $this->request->data;
        $d['store_id'] = $this->_appSession->read('App.selectedStoreID');
        $ent = $this->Products->newEntity($d);
        if($this->Products->save($ent)){
            $result = [
                "error" => 0,
                "msg" => "Product saved successfully..."
            ];
        }else{
            $result = [
                "error" => 1,
                "msg" => "Unable to save product...",
                "error" => $ent->errors()
            ];
        }
        
        $this->set("result",$result);
        $this->set('_serialize', ['result']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit()
    {
        $this->request->allowMethod(['post','put']);
        $d = $this->request->data;
        $ent = $this->Products->get($d['id']);
        $ent->product_name = $d['product_name'];
        if($this->Products->save($ent)){
            $result = [
                "error" => 0,
                "msg" => "Product saved successfully..."
            ];
        }else{
            $result = [
                "error" => 1,
                "msg" => "Unable to save product...",
                "error" => $ent->errors()
            ];
        }
        
        $this->set("result",$result);
        $this->set('_serialize', ['result']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Product id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $product = $this->Products->get($id);
        if ($this->Products->delete($product)) {
            $result = [
                "error" => 0,
                "msg" => __('The product has been deleted.')
            ];
        } else {
            $result = [
                "error" => 1,
                "msg" => __('The product could not be deleted. Please, try again.')
            ];
        }
        $this->set("result",$result);
        $this->set('_serialize', ['result']);
    }
}
