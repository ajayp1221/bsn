<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * Campaigns Controller
 *
 * @property \App\Model\Table\CampaignsTable $Campaigns
 */
class CampaignsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Stores']
        ];
        $this->set('campaigns', $this->paginate($this->Campaigns));
        $this->set('_serialize', ['campaigns']);
    }

    /**
     * View method
     *
     * @param string|null $id Campaign id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $campaign = $this->Campaigns->get($id, [
            'contain' => ['Stores', 'Messages']
        ]);
        $this->set('campaign', $campaign);
        $this->set('_serialize', ['campaign']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $campaign = $this->Campaigns->newEntity();
        if ($this->request->is('post')) {
            $campaign = $this->Campaigns->patchEntity($campaign, $this->request->data);
            if ($this->Campaigns->save($campaign)) {
                $this->Flash->success(__('The campaign has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The campaign could not be saved. Please, try again.'));
            }
        }
        $stores = $this->Campaigns->Stores->find('list', ['limit' => 200]);
        $this->set(compact('campaign', 'stores'));
        $this->set('_serialize', ['campaign']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Campaign id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $campaign = $this->Campaigns->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $campaign = $this->Campaigns->patchEntity($campaign, $this->request->data);
            if ($this->Campaigns->save($campaign)) {
                $this->Flash->success(__('The campaign has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The campaign could not be saved. Please, try again.'));
            }
        }
        $stores = $this->Campaigns->Stores->find('list', ['limit' => 200]);
        $this->set(compact('campaign', 'stores'));
        $this->set('_serialize', ['campaign']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Campaign id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $campaign = $this->Campaigns->get($id);
        if ($this->Campaigns->delete($campaign)) {
            $this->Flash->success(__('The campaign has been deleted.'));
        } else {
            $this->Flash->error(__('The campaign could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
