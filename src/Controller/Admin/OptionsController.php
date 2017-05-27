<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Options Controller
 *
 * @property \App\Model\Table\OptionsTable $Options
 */
class OptionsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Questions']
        ];
        $this->set('options', $this->paginate($this->Options));
        $this->set('_serialize', ['options']);
    }

    /**
     * View method
     *
     * @param string|null $id Option id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $option = $this->Options->get($id, [
            'contain' => ['Questions', 'Answers']
        ]);
        $this->set('option', $option);
        $this->set('_serialize', ['option']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $option = $this->Options->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['status'] = 1;
            $option = $this->Options->patchEntity($option, $this->request->data);
            if ($this->Options->save($option)) {
                $this->Flash->success(__('The option has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The option could not be saved. Please, try again.'));
            }
        }
        $questions = $this->Options->Questions->find('list', ['limit' => 200]);
        $this->set(compact('option', 'questions'));
        $this->set('_serialize', ['option']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Option id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $option = $this->Options->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $option = $this->Options->patchEntity($option, $this->request->data);
            if ($this->Options->save($option)) {
                $this->Flash->success(__('The option has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The option could not be saved. Please, try again.'));
            }
        }
        $questions = $this->Options->Questions->find('list', ['limit' => 200]);
        $this->set(compact('option', 'questions'));
        $this->set('_serialize', ['option']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Option id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $softDeleted  = $this->Options->updateAll(['soft_deleted' => 1], ['id' => $id]);
        if ($softDeleted) {
            $this->Flash->success(__('The option has been deleted.'));
        } else {
            $this->Flash->error(__('The option could not be deleted. Please, try again.'));
        }
        return $this->redirect($this->referer());
    }
}
