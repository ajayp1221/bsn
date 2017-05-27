<?php
namespace App\Controller\Api\V1;

use App\Controller\Api\V1\AppController;

/**
 * ShareScreen Controller
 *
 * @property \App\Model\Table\ShareScreenTable $ShareScreen
 */
class ShareScreenController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index($store_id = null)
    {
        $this->paginate = [
            'contain' => ['Stores']
        ];
        $this->set('shareScreen', $this->ShareScreen->find()->where(['store_id'=>$store_id])->first());
        $this->set('_serialize', ['shareScreen']);
    }

    /**
     * View method
     *
     * @param string|null $id Recommend Screen id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shareScreen = $this->ShareScreen->get($id, [
            'contain' => ['Stores']
        ]);
        $this->set('shareScreen', $shareScreen);
        $this->set('_serialize', ['shareScreen']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        if($shareScreen = $this->ShareScreen->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID')
                ])->first()){
            
        }else{
            $shareScreen = $this->ShareScreen->newEntity();
        }
        if ($this->request->is(['post','put'])) {
            $shareScreen = $this->ShareScreen->patchEntity($shareScreen, $this->request->data);
            if ($result = $this->ShareScreen->save($shareScreen)) {
                $this->Flash->success(__('The recommend screen has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The recommend screen could not be saved. Please, try again.'));
            }
        }
        $stores = $this->ShareScreen->Stores->find('list', ['limit' => 200]);
        $this->set(compact('shareScreen', 'stores'));
        $this->set('_serialize', ['shareScreen']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Recommend Screen id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shareScreen = $this->ShareScreen->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shareScreen = $this->ShareScreen->patchEntity($shareScreen, $this->request->data);
            if ($this->ShareScreen->save($shareScreen)) {
                $this->Flash->success(__('The recommend screen has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The recommend screen could not be saved. Please, try again.'));
            }
        }
        $stores = $this->ShareScreen->Stores->find('list', ['limit' => 200]);
        $this->set(compact('shareScreen', 'stores'));
        $this->set('_serialize', ['shareScreen']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Recommend Screen id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shareScreen = $this->ShareScreen->get($id);
        if ($this->ShareScreen->delete($shareScreen)) {
            $this->Flash->success(__('The recommend screen has been deleted.'));
        } else {
            $this->Flash->error(__('The recommend screen could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
