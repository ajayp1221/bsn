<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Socialshares Controller
 *
 * @property \App\Model\Table\SocialsharesTable $Socialshares
 */
class SocialsharesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('socialshares', $this->paginate($this->Socialshares));
        $this->set('_serialize', ['socialshares']);
    }

    /**
     * View method
     *
     * @param string|null $id Socialshare id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $socialshare = $this->Socialshares->get($id, [
            'contain' => []
        ]);
        $this->set('socialshare', $socialshare);
        $this->set('_serialize', ['socialshare']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $socialshare = $this->Socialshares->newEntity();
        if ($this->request->is('post')) {
            $socialshare = $this->Socialshares->patchEntity($socialshare, $this->request->data);
            if ($this->Socialshares->save($socialshare)) {
                $this->Flash->success(__('The socialshare has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The socialshare could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('socialshare'));
        $this->set('_serialize', ['socialshare']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Socialshare id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $socialshare = $this->Socialshares->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $socialshare = $this->Socialshares->patchEntity($socialshare, $this->request->data);
            if ($this->Socialshares->save($socialshare)) {
                $this->Flash->success(__('The socialshare has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The socialshare could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('socialshare'));
        $this->set('_serialize', ['socialshare']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Socialshare id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $softDeleted = $this->Socialshares->updateAll(['soft_deleted' => 1], ['id' => $id]);
        if ($softDeleted) {
            $this->Flash->success(__('The socialshare has been deleted.'));
        } else {
            $this->Flash->error(__('The socialshare could not be deleted. Please, try again.'));
        }
        return $this->redirect($this->referer());
    }
}
