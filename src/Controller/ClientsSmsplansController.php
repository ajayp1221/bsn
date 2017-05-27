<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ClientsSmsplans Controller
 *
 * @property \App\Model\Table\ClientsSmsplansTable $ClientsSmsplans
 */
class ClientsSmsplansController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Clients', 'Smsplans']
        ];
        $clientsSmsplans = $this->paginate($this->ClientsSmsplans);

        $this->set(compact('clientsSmsplans'));
        $this->set('_serialize', ['clientsSmsplans']);
    }

    /**
     * View method
     *
     * @param string|null $id Clients Smsplan id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $clientsSmsplan = $this->ClientsSmsplans->get($id, [
            'contain' => ['Clients', 'Smsplans']
        ]);

        $this->set('clientsSmsplan', $clientsSmsplan);
        $this->set('_serialize', ['clientsSmsplan']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $clientsSmsplan = $this->ClientsSmsplans->newEntity();
        if ($this->request->is('post')) {
            $clientsSmsplan = $this->ClientsSmsplans->patchEntity($clientsSmsplan, $this->request->data);
            if ($this->ClientsSmsplans->save($clientsSmsplan)) {
                $this->Flash->success(__('The clients smsplan has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The clients smsplan could not be saved. Please, try again.'));
            }
        }
        $clients = $this->ClientsSmsplans->Clients->find('list', ['limit' => 200]);
        $smsplans = $this->ClientsSmsplans->Smsplans->find('list', ['limit' => 200]);
        $this->set(compact('clientsSmsplan', 'clients', 'smsplans'));
        $this->set('_serialize', ['clientsSmsplan']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Clients Smsplan id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $clientsSmsplan = $this->ClientsSmsplans->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $clientsSmsplan = $this->ClientsSmsplans->patchEntity($clientsSmsplan, $this->request->data);
            if ($this->ClientsSmsplans->save($clientsSmsplan)) {
                $this->Flash->success(__('The clients smsplan has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The clients smsplan could not be saved. Please, try again.'));
            }
        }
        $clients = $this->ClientsSmsplans->Clients->find('list', ['limit' => 200]);
        $smsplans = $this->ClientsSmsplans->Smsplans->find('list', ['limit' => 200]);
        $this->set(compact('clientsSmsplan', 'clients', 'smsplans'));
        $this->set('_serialize', ['clientsSmsplan']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Clients Smsplan id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $clientsSmsplan = $this->ClientsSmsplans->get($id);
        if ($this->ClientsSmsplans->delete($clientsSmsplan)) {
            $this->Flash->success(__('The clients smsplan has been deleted.'));
        } else {
            $this->Flash->error(__('The clients smsplan could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
