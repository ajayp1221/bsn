<?php
namespace App\Controller\Api\V1;

use App\Controller\Api\V1\AppController;

/**
 * Questions Controller
 *
 * @property \App\Model\Table\QuestionsTable $Questions
 */
class QuestionsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index($store_id = null)
    {
//        $this->paginate = [
//            'contain' => ['Stores']
//        ];
        $this->set('questions', $this->Questions->find()->contain(['Stores','Options'])->where([
            'Questions.store_id' => $store_id,
            'Questions.soft_deleted' => 0
        ])->order(['order_seq asc'])->all());
        $this->set('_serialize', ['questions']);
    }
    
    /**
     * Index for punjabi bagh
     * @param type $store_id
     */
    public function index2($store_id = null)
    {
//        $this->paginate = [
//            'contain' => ['Stores']
//        ];
        $session = $this->request->session();
//        $session->write('Api.customer_type', 'N');
        $buyer = $session->read('Api.customer_type');
        $this->set('questions', $x = $this->Questions->find()->contain(['Stores','Options'])->where([
            'Questions.store_id' => $store_id,
            'Questions.soft_deleted' => 0,
//            'OR' => [
//                'Questions.buyer IN' => [$buyer,'BN']
//            ]
        ])->order(['order_seq asc'])->all());
        $this->set('_serialize', ['questions']);
    }

    /**
     * View method
     *
     * @param string|null $id Question id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $question = $this->Questions->get($id, [
            'contain' => ['Stores', 'Answers', 'Options']
        ]);
        $this->set('question', $question);
        $this->set('_serialize', ['question']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $question = $this->Questions->newEntity();
        if ($this->request->is('post')) {
            $question = $this->Questions->patchEntity($question, $this->request->data);
            if ($this->Questions->save($question)) {
                $this->Flash->success(__('The question has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The question could not be saved. Please, try again.'));
            }
        }
        $stores = $this->Questions->Stores->find('list', ['limit' => 200]);
        $this->set(compact('question', 'stores'));
        $this->set('_serialize', ['question']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Question id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $question = $this->Questions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $question = $this->Questions->patchEntity($question, $this->request->data);
            if ($this->Questions->save($question)) {
                $this->Flash->success(__('The question has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The question could not be saved. Please, try again.'));
            }
        }
        $stores = $this->Questions->Stores->find('list', ['limit' => 200]);
        $this->set(compact('question', 'stores'));
        $this->set('_serialize', ['question']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Question id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $question = $this->Questions->get($id);
        if ($this->Questions->delete($question)) {
            $this->Flash->success(__('The question has been deleted.'));
        } else {
            $this->Flash->error(__('The question could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
