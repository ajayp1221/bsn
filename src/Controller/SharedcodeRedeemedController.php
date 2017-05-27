<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SharedcodeRedeemed Controller
 *
 * @property \App\Model\Table\SharedcodeRedeemedTable $SharedcodeRedeemed
 */
class SharedcodeRedeemedController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Customers', 'Sharedcodes']
        ];
        $sharedcodeRedeemed = $this->paginate($this->SharedcodeRedeemed);

        $this->set(compact('sharedcodeRedeemed'));
        $this->set('_serialize', ['sharedcodeRedeemed']);
    }

    /**
     * View method
     *
     * @param string|null $id Sharedcode Redeemed id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sharedcodeRedeemed = $this->SharedcodeRedeemed->get($id, [
            'contain' => ['Customers', 'Sharedcodes']
        ]);

        $this->set('sharedcodeRedeemed', $sharedcodeRedeemed);
        $this->set('_serialize', ['sharedcodeRedeemed']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sharedcodeRedeemed = $this->SharedcodeRedeemed->newEntity();
        if ($this->request->is('post')) {
            $sharedcodeRedeemed = $this->SharedcodeRedeemed->patchEntity($sharedcodeRedeemed, $this->request->data);
            if ($this->SharedcodeRedeemed->save($sharedcodeRedeemed)) {
                $this->Flash->success(__('The sharedcode redeemed has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sharedcode redeemed could not be saved. Please, try again.'));
            }
        }
        $customers = $this->SharedcodeRedeemed->Customers->find('list', ['limit' => 200]);
        $sharedcodes = $this->SharedcodeRedeemed->Sharedcodes->find('list', ['limit' => 200]);
        $this->set(compact('sharedcodeRedeemed', 'customers', 'sharedcodes'));
        $this->set('_serialize', ['sharedcodeRedeemed']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sharedcode Redeemed id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sharedcodeRedeemed = $this->SharedcodeRedeemed->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sharedcodeRedeemed = $this->SharedcodeRedeemed->patchEntity($sharedcodeRedeemed, $this->request->data);
            if ($this->SharedcodeRedeemed->save($sharedcodeRedeemed)) {
                $this->Flash->success(__('The sharedcode redeemed has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sharedcode redeemed could not be saved. Please, try again.'));
            }
        }
        $customers = $this->SharedcodeRedeemed->Customers->find('list', ['limit' => 200]);
        $sharedcodes = $this->SharedcodeRedeemed->Sharedcodes->find('list', ['limit' => 200]);
        $this->set(compact('sharedcodeRedeemed', 'customers', 'sharedcodes'));
        $this->set('_serialize', ['sharedcodeRedeemed']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sharedcode Redeemed id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sharedcodeRedeemed = $this->SharedcodeRedeemed->get($id);
        if ($this->SharedcodeRedeemed->delete($sharedcodeRedeemed)) {
            $this->Flash->success(__('The sharedcode redeemed has been deleted.'));
        } else {
            $this->Flash->error(__('The sharedcode redeemed could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
