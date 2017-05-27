<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Excludes Controller
 *
 * @property \App\Model\Table\ExcludesTable $Excludes
 * @property \App\Model\Table\StoresTable $Stores
 */
class ExcludesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'order' => [
                'Excludes.model_name' => 'DESC'
            ]
        ];
        $this->set('excludes', $this->paginate($this->Excludes));
        $this->set('_serialize', ['excludes']);
    }

    /**
     * View method
     *
     * @param string|null $id Exclude id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $exclude = $this->Excludes->get($id, [
            'contain' => []
        ]);
        $this->set('exclude', $exclude);
        $this->set('_serialize', ['exclude']);
    }

    /**
     * Add contact method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function contact()
    {
        $exclude = $this->Excludes->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['status'] = 1;
            $contacts = explode(',', $this->request->data['value']);
            foreach($contacts as $contact){
                $check = $this->Excludes->findByValue($contact)->first();
                if(!$check){
                    $this->request->data['value'] = $contact;
                    $exclude1 = '';
                    $exclude1 = $this->Excludes->newEntity($this->request->data);
                    $this->Excludes->save($exclude1);
                }
            }
            return $this->redirect(['action' => 'index']);
        }
        $this->set(compact('exclude'));
        $this->set('_serialize', ['exclude']);
    }
    /**
     * Add Store method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function store()
    {
        $exclude = $this->Excludes->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['status'] = 1;
            $exclude = $this->Excludes->patchEntity($exclude, $this->request->data);
            $check = $this->Excludes->findByValue($this->request->data['value'])->first();
            if(!$check){
                if ($this->Excludes->save($exclude)) {
                    $this->Flash->success(__('The exclude has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The exclude could not be saved. Please, try again.'));
                }
            }
        }
        $this->loadModel('Stores');
        $stores = $this->Stores->find('list', ['limit' => 200]);
        $this->set(compact('exclude','stores'));
        $this->set('_serialize', ['exclude']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Exclude id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $exclude = $this->Excludes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $exclude = $this->Excludes->patchEntity($exclude, $this->request->data);
            if ($this->Excludes->save($exclude)) {
                $this->Flash->success(__('The exclude has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The exclude could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('exclude'));
        $this->set('_serialize', ['exclude']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Exclude id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $exclude = $this->Excludes->get($id);
        if ($this->Excludes->delete($exclude)) {
            $this->Flash->success(__('The exclude has been deleted.'));
        } else {
            $this->Flash->error(__('The exclude could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
