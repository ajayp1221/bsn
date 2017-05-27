<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Albumshares Controller
 *
 * @property \App\Model\Table\AlbumsharesTable $Albumshares
 */
class AlbumsharesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $albumshares = $this->paginate($this->Albumshares);

        $this->set(compact('albumshares'));
        $this->set('_serialize', ['albumshares']);
    }

    /**
     * View method
     *
     * @param string|null $id Albumshare id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $albumshare = $this->Albumshares->get($id, [
            'contain' => []
        ]);

        $this->set('albumshare', $albumshare);
        $this->set('_serialize', ['albumshare']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $albumshare = $this->Albumshares->newEntity();
        if ($this->request->is('post')) {
            $albumshare = $this->Albumshares->patchEntity($albumshare, $this->request->data);
            if ($this->Albumshares->save($albumshare)) {
                $this->Flash->success(__('The albumshare has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The albumshare could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('albumshare'));
        $this->set('_serialize', ['albumshare']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Albumshare id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $albumshare = $this->Albumshares->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $albumshare = $this->Albumshares->patchEntity($albumshare, $this->request->data);
            if ($this->Albumshares->save($albumshare)) {
                $this->Flash->success(__('The albumshare has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The albumshare could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('albumshare'));
        $this->set('_serialize', ['albumshare']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Albumshare id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $albumshare = $this->Albumshares->get($id);
        if ($this->Albumshares->delete($albumshare)) {
            $this->Flash->success(__('The albumshare has been deleted.'));
        } else {
            $this->Flash->error(__('The albumshare could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
