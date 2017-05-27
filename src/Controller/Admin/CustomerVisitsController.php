<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * CustomerVisits Controller
 *
 * @property \App\Model\Table\CustomerVisitsTable $CustomerVisits
 */
class CustomerVisitsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Customers', 'Stores']
        ];
        $this->set('customerVisits', $this->paginate($this->CustomerVisits));
        $this->set('_serialize', ['customerVisits']);
    }

    /**
     * View method
     *
     * @param string|null $id Customer Visit id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customerVisit = $this->CustomerVisits->get($id, [
            'contain' => ['Customers', 'Stores']
        ]);
        $this->set('customerVisit', $customerVisit);
        $this->set('_serialize', ['customerVisit']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customerVisit = $this->CustomerVisits->newEntity();
        if ($this->request->is('post')) {
            $customerVisit = $this->CustomerVisits->patchEntity($customerVisit, $this->request->data);
            if ($this->CustomerVisits->save($customerVisit)) {
                $this->Flash->success(__('The customer visit has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The customer visit could not be saved. Please, try again.'));
            }
        }
        $customers = $this->CustomerVisits->Customers->find('list', ['limit' => 200]);
        $stores = $this->CustomerVisits->Stores->find('list', ['limit' => 200]);
        $this->set(compact('customerVisit', 'customers', 'stores'));
        $this->set('_serialize', ['customerVisit']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer Visit id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $customerVisit = $this->CustomerVisits->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customerVisit = $this->CustomerVisits->patchEntity($customerVisit, $this->request->data);
            if ($this->CustomerVisits->save($customerVisit)) {
                $this->Flash->success(__('The customer visit has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The customer visit could not be saved. Please, try again.'));
            }
        }
        $customers = $this->CustomerVisits->Customers->find('list', ['limit' => 200]);
        $stores = $this->CustomerVisits->Stores->find('list', ['limit' => 200]);
        $this->set(compact('customerVisit', 'customers', 'stores'));
        $this->set('_serialize', ['customerVisit']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer Visit id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customerVisit = $this->CustomerVisits->get($id);
        if ($this->CustomerVisits->delete($customerVisit)) {
            $this->Flash->success(__('The customer visit has been deleted.'));
        } else {
            $this->Flash->error(__('The customer visit could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
