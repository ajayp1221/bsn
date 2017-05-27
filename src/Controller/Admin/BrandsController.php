<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Brands Controller
 *
 * @property \App\Model\Table\BrandsTable $Brands
 */
class BrandsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Clients']
        ];
        $this->set('brands', $this->paginate($this->Brands));
        $this->set('_serialize', ['brands']);
    }

    /**
     * View method
     *
     * @param string|null $id Brand id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $brand = $this->Brands->get($id, [
            'contain' => ['Clients', 'Stores']
        ]);
        $this->set('brand', $brand);
        $this->set('_serialize', ['brand']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $brand = $this->Brands->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['status'] = 1;
            $brand = $this->Brands->patchEntity($brand, $this->request->data);
            if ($this->Brands->save($brand)) {
                $this->Flash->success(__('The brand has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The brand could not be saved. Please, try again.'));
            }
        }
        $clients = $this->Brands->Clients->find('list', ['limit' => 200])->order(['id' => 'desc']);
        $this->set(compact('brand', 'clients'));
        $this->set('_serialize', ['brand']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Brand id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $brand = $this->Brands->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $brand = $this->Brands->patchEntity($brand, $this->request->data);
            if ($this->Brands->save($brand)) {
                $this->Flash->success(__('The brand has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The brand could not be saved. Please, try again.'));
            }
        }
        $clients = $this->Brands->Clients->find('list', ['limit' => 200]);
        $this->set(compact('brand', 'clients'));
        $this->set('_serialize', ['brand']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Brand id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $softDeleted = $this->Brands->updateAll(['soft_deleted' => 1], ['id' => $id]);
        if ($softDeleted) {
            $this->Flash->success(__('The brand has been deleted.'));
        } else {
            $this->Flash->error(__('The brand could not be deleted. Please, try again.'));
        }
        return $this->redirect($this->referer());
    }
}
