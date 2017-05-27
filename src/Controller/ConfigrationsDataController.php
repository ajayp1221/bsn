<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ConfigrationsData Controller
 *
 * @property \App\Model\Table\ConfigrationsDataTable $ConfigrationsData
 */
class ConfigrationsDataController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $configrationsData = $this->paginate($this->ConfigrationsData);

        $this->set(compact('configrationsData'));
        $this->set('_serialize', ['configrationsData']);
    }

    /**
     * View method
     *
     * @param string|null $id Configrations Data id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $configrationsData = $this->ConfigrationsData->get($id, [
            'contain' => []
        ]);

        $this->set('configrationsData', $configrationsData);
        $this->set('_serialize', ['configrationsData']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $configrationsData = $this->ConfigrationsData->newEntity();
        if ($this->request->is('post')) {
            $configrationsData = $this->ConfigrationsData->patchEntity($configrationsData, $this->request->data);
            if ($this->ConfigrationsData->save($configrationsData)) {
                $this->Flash->success(__('The configrations data has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The configrations data could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('configrationsData'));
        $this->set('_serialize', ['configrationsData']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Configrations Data id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $configrationsData = $this->ConfigrationsData->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $configrationsData = $this->ConfigrationsData->patchEntity($configrationsData, $this->request->data);
            if ($this->ConfigrationsData->save($configrationsData)) {
                $this->Flash->success(__('The configrations data has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The configrations data could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('configrationsData'));
        $this->set('_serialize', ['configrationsData']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Configrations Data id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $configrationsData = $this->ConfigrationsData->get($id);
        if ($this->ConfigrationsData->delete($configrationsData)) {
            $this->Flash->success(__('The configrations data has been deleted.'));
        } else {
            $this->Flash->error(__('The configrations data could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
