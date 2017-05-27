<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * Purchases Controller
 *
 * @property \App\Model\Table\PurchasesTable $Purchases
 */
class PurchasesController extends AppController
{
    
    
    
    /**
     *  Save method
     * save data from android api
     */
    public function save(){
        
        $d = $this->request->data;
        $data = json_decode($d['result']);
        $dt = [];
        foreach($data->final as $r){
            $dt[] = [
                'store_id' => $d['store_id'],
                'customer_id' => $d['customer_id'],
                'product_id' => $r->product_id,
                'product_name' => $r->product_name,
                'qty' => 1,
                'price' => $r->product_price,
                'sold_on' => time()
            ];
        }
        $entities = $this->Purchases->newEntities($dt);
        foreach($entities as $entity){
            $this->Purchases->save($entity);
        }
        file_put_contents(WWW_ROOT.'rq.txt', print_r($entities,true));
        $result = 1;
        $this->set('result', $result);
        $this->set('_serialize', ['result']);
    }



    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Stores', 'Products', 'Customers']
        ];
        $this->set('purchases', $this->paginate($this->Purchases));
        $this->set('_serialize', ['purchases']);
    }

    /**
     * View method
     *
     * @param string|null $id Purchase id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $purchase = $this->Purchases->get($id, [
            'contain' => ['Stores', 'Products', 'Customers']
        ]);
        $this->set('purchase', $purchase);
        $this->set('_serialize', ['purchase']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $purchase = $this->Purchases->newEntity();
        if ($this->request->is('post')) {
            $purchase = $this->Purchases->patchEntity($purchase, $this->request->data);
            if ($this->Purchases->save($purchase)) {
                $this->Flash->success(__('The purchase has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The purchase could not be saved. Please, try again.'));
            }
        }
        $stores = $this->Purchases->Stores->find('list', ['limit' => 200]);
        $products = $this->Purchases->Products->find('list', ['limit' => 200]);
        $customers = $this->Purchases->Customers->find('list', ['limit' => 200]);
        $this->set(compact('purchase', 'stores', 'products', 'customers'));
        $this->set('_serialize', ['purchase']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Purchase id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $purchase = $this->Purchases->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $purchase = $this->Purchases->patchEntity($purchase, $this->request->data);
            if ($this->Purchases->save($purchase)) {
                $this->Flash->success(__('The purchase has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The purchase could not be saved. Please, try again.'));
            }
        }
        $stores = $this->Purchases->Stores->find('list', ['limit' => 200]);
        $products = $this->Purchases->Products->find('list', ['limit' => 200]);
        $customers = $this->Purchases->Customers->find('list', ['limit' => 200]);
        $this->set(compact('purchase', 'stores', 'products', 'customers'));
        $this->set('_serialize', ['purchase']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Purchase id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $purchase = $this->Purchases->get($id);
        if ($this->Purchases->delete($purchase)) {
            $this->Flash->success(__('The purchase has been deleted.'));
        } else {
            $this->Flash->error(__('The purchase could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
