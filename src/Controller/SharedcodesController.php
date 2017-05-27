<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Sharedcodes Controller
 *
 * @property \App\Model\Table\SharedcodesTable $Sharedcodes
 */
class SharedcodesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Clients', 'Customers', 'Stores']
        ];
        $sharedcodes = $this->paginate($this->Sharedcodes);

        $this->set(compact('sharedcodes'));
        $this->set('_serialize', ['sharedcodes']);
    }

    /**
     * View method
     *
     * @param string|null $id Sharedcode id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sharedcode = $this->Sharedcodes->get($id, [
            'contain' => ['Clients', 'Customers', 'Stores', 'SharedcodeRedeemed']
        ]);

        $this->set('sharedcode', $sharedcode);
        $this->set('_serialize', ['sharedcode']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sharedcode = $this->Sharedcodes->newEntity();
        if ($this->request->is('post')) {
            $sharedcode = $this->Sharedcodes->patchEntity($sharedcode, $this->request->data);
            if ($this->Sharedcodes->save($sharedcode)) {
                $this->Flash->success(__('The sharedcode has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sharedcode could not be saved. Please, try again.'));
            }
        }
        $clients = $this->Sharedcodes->Clients->find('list', ['limit' => 200]);
        $customers = $this->Sharedcodes->Customers->find('list', ['limit' => 200]);
        $stores = $this->Sharedcodes->Stores->find('list', ['limit' => 200]);
        $this->set(compact('sharedcode', 'clients', 'customers', 'stores'));
        $this->set('_serialize', ['sharedcode']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sharedcode id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sharedcode = $this->Sharedcodes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sharedcode = $this->Sharedcodes->patchEntity($sharedcode, $this->request->data);
            if ($this->Sharedcodes->save($sharedcode)) {
                $this->Flash->success(__('The sharedcode has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sharedcode could not be saved. Please, try again.'));
            }
        }
        $clients = $this->Sharedcodes->Clients->find('list', ['limit' => 200]);
        $customers = $this->Sharedcodes->Customers->find('list', ['limit' => 200]);
        $stores = $this->Sharedcodes->Stores->find('list', ['limit' => 200]);
        $this->set(compact('sharedcode', 'clients', 'customers', 'stores'));
        $this->set('_serialize', ['sharedcode']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sharedcode id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sharedcode = $this->Sharedcodes->get($id);
        if ($this->Sharedcodes->delete($sharedcode)) {
            $this->Flash->success(__('The sharedcode has been deleted.'));
        } else {
            $this->Flash->error(__('The sharedcode could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
