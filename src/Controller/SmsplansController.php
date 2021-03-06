<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Smsplans Controller
 *
 * @property \App\Model\Table\SmsplansTable $Smsplans
 */
class SmsplansController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $smsplans = $this->paginate($this->Smsplans);

        $this->set(compact('smsplans'));
        $this->set('_serialize', ['smsplans']);
    }

    /**
     * View method
     *
     * @param string|null $id Smsplan id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $smsplan = $this->Smsplans->get($id, [
            'contain' => ['Clients']
        ]);

        $this->set('smsplan', $smsplan);
        $this->set('_serialize', ['smsplan']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $smsplan = $this->Smsplans->newEntity();
        if ($this->request->is('post')) {
            $smsplan = $this->Smsplans->patchEntity($smsplan, $this->request->data);
            if ($this->Smsplans->save($smsplan)) {
                $this->Flash->success(__('The smsplan has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The smsplan could not be saved. Please, try again.'));
            }
        }
        $clients = $this->Smsplans->Clients->find('list', ['limit' => 200]);
        $this->set(compact('smsplan', 'clients'));
        $this->set('_serialize', ['smsplan']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Smsplan id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $smsplan = $this->Smsplans->get($id, [
            'contain' => ['Clients']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $smsplan = $this->Smsplans->patchEntity($smsplan, $this->request->data);
            if ($this->Smsplans->save($smsplan)) {
                $this->Flash->success(__('The smsplan has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The smsplan could not be saved. Please, try again.'));
            }
        }
        $clients = $this->Smsplans->Clients->find('list', ['limit' => 200]);
        $this->set(compact('smsplan', 'clients'));
        $this->set('_serialize', ['smsplan']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Smsplan id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $smsplan = $this->Smsplans->get($id);
        if ($this->Smsplans->delete($smsplan)) {
            $this->Flash->success(__('The smsplan has been deleted.'));
        } else {
            $this->Flash->error(__('The smsplan could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
