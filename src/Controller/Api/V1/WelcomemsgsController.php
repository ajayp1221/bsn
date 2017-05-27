<?php
namespace App\Controller\Api\V1;

use App\Controller\Api\V1\AppController;

/**
 * Welcomemsgs Controller
 *
 * @property \App\Model\Table\WelcomemsgsTable $Welcomemsgs
 */
class WelcomemsgsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Customers']
        ];
        $this->set('welcomemsgs', $this->paginate($this->Welcomemsgs));
        $this->set('_serialize', ['welcomemsgs']);
    }

    /**
     * View method
     *
     * @param string|null $id Welcomemsg id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $welcomemsg = $this->Welcomemsgs->get($id, [
            'contain' => ['Customers']
        ]);
        $this->set('welcomemsg', $welcomemsg);
        $this->set('_serialize', ['welcomemsg']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $welcomemsg = $this->Welcomemsgs->newEntity();
        if ($this->request->is('post')) {
            $welcomemsg = $this->Welcomemsgs->patchEntity($welcomemsg, $this->request->data);
            if ($this->Welcomemsgs->save($welcomemsg)) {
                $this->Flash->success(__('The welcomemsg has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The welcomemsg could not be saved. Please, try again.'));
            }
        }
        $customers = $this->Welcomemsgs->Customers->find('list', ['limit' => 200]);
        $this->set(compact('welcomemsg', 'customers'));
        $this->set('_serialize', ['welcomemsg']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Welcomemsg id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $welcomemsg = $this->Welcomemsgs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $welcomemsg = $this->Welcomemsgs->patchEntity($welcomemsg, $this->request->data);
            if ($this->Welcomemsgs->save($welcomemsg)) {
                $this->Flash->success(__('The welcomemsg has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The welcomemsg could not be saved. Please, try again.'));
            }
        }
        $customers = $this->Welcomemsgs->Customers->find('list', ['limit' => 200]);
        $this->set(compact('welcomemsg', 'customers'));
        $this->set('_serialize', ['welcomemsg']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Welcomemsg id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $welcomemsg = $this->Welcomemsgs->get($id);
        if ($this->Welcomemsgs->delete($welcomemsg)) {
            $this->Flash->success(__('The welcomemsg has been deleted.'));
        } else {
            $this->Flash->error(__('The welcomemsg could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
