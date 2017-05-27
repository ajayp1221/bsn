<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * Productcats Controller
 *
 * @property \App\Model\Table\ProductcatsTable $Productcats
 */
class ProductcatsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Stores']
        ];
        $this->set('productcats', $this->paginate($this->Productcats));
        $this->set('_serialize', ['productcats']);
    }

    /**
     * View method
     *
     * @param string|null $id Productcat id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productcat = $this->Productcats->get($id, [
            'contain' => ['Stores', 'Products']
        ]);
        $this->set('productcat', $productcat);
        $this->set('_serialize', ['productcat']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productcat = $this->Productcats->newEntity();
        if ($this->request->is('post')) {
            $productcat = $this->Productcats->patchEntity($productcat, $this->request->data);
            if ($this->Productcats->save($productcat)) {
                $this->Flash->success(__('The productcat has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The productcat could not be saved. Please, try again.'));
            }
        }
        $stores = $this->Productcats->Stores->find('list', ['limit' => 200]);
        $this->set(compact('productcat', 'stores'));
        $this->set('_serialize', ['productcat']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Productcat id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productcat = $this->Productcats->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productcat = $this->Productcats->patchEntity($productcat, $this->request->data);
            if ($this->Productcats->save($productcat)) {
                $this->Flash->success(__('The productcat has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The productcat could not be saved. Please, try again.'));
            }
        }
        $stores = $this->Productcats->Stores->find('list', ['limit' => 200]);
        $this->set(compact('productcat', 'stores'));
        $this->set('_serialize', ['productcat']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Productcat id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productcat = $this->Productcats->get($id);
        if ($this->Productcats->delete($productcat)) {
            $this->Flash->success(__('The productcat has been deleted.'));
        } else {
            $this->Flash->error(__('The productcat could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
