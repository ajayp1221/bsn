<?php
namespace App\Controller;

use App\Controller\AppController;

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
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if($this->request->is('post')){
            $optTbl = \Cake\ORM\TableRegistry::get('Options');
            $d = $this->request->data;
            
            /** Data Configure Defaults Start **/
            foreach($d['options'] as &$opts){
                $opts['soft_deleted'] = 0;
                $opts['status'] = 1;
                $opts['created'] = time();
            }
            $d['soft_deleted'] = 0;
            $d['no_skip'] = 0;
            $d['no_delete'] = 0;
            $d['group_mark'] = 0;
            $d['buyer'] = '';
            $d['order_seq'] = 0;
            $d['status'] = 1;
            $d['view_type'] = 1;
            $d['store_id'] = $this->_appSession->read('App.selectedStoreID');
            $d['created'] = time();
            /** end **/
            
            if(isset($d['id'])){
                $optTbl->deleteAll([
                    "question_id" => $d['id']
                ]);
                $qent = $this->Questions->get($d['id']);
                $qent = $this->Questions->patchEntity($qent, $d);
            }else{
                $qent = $this->Questions->newEntity($d);
            }
            
            
//            debug($newQue); exit;
            if($this->Questions->save($qent, ['associated' => ['Options']])){
                $result = [
                    "error" => 0,
                    "msg" => "Question saved successfully...",
                    "data" => $d
                ];
            }else{
                $result = [
                    "error" => 1,
                    "msg" => "Question can't be saved...",
                    "data" => $qent->errors()
                ];
            }
            
            
            $this->set("result",$result);
            $this->set('_serialize', ['result']);
        }else{
            $this->paginate = [
                'contain' => ['Options']
            ];
            $questions = $this->paginate($this->Questions->find()->where([
                "store_id" => $this->_appSession->read('App.selectedStoreID')
            ]));

            $this->set(compact('questions'));
            $this->set('_serialize', ['questions']);
        }
    }

    /**
     * View method
     *
     * @param string|null $id Question id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
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
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
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
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
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
     * Deactivate method
     *
     * @param string|null $id Question id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deactivate($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $question = $this->Questions->get($id);
        $question->soft_deleted = 1;
        if ($this->Questions->save($question)) {
            $result = [
                "error" => 0,
                "msg" => __('The question has been deactivated.'),
            ];
        } else {
            $result = [
                "error" => 1,
                "msg" => __('The question could not be deactivated. Please, try again.'),
            ];
        }
        $this->set("result",$result);
        $this->set('_serialize', ['result']);
    }
    public function activate($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $question = $this->Questions->get($id);
        $question->soft_deleted = 0;
        if ($this->Questions->save($question)) {
            $result = [
                "error" => 0,
                "msg" => __('The question has been activated.'),
            ];
        } else {
            $result = [
                "error" => 1,
                "msg" => __('The question could not be activated. Please, try again.'),
            ];
        }
        $this->set("result",$result);
        $this->set('_serialize', ['result']);
    }
}
