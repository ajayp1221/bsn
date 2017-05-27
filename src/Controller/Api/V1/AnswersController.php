<?php
namespace App\Controller\Api\V1;

use App\Controller\Api\V1\AppController;

/**
 * Answers Controller
 *
 * @property \App\Model\Table\AnswersTable $Answers
 */
class AnswersController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Customers', 'Questions', 'Options']
        ];
        $this->set('answers', $this->paginate($this->Answers));
        $this->set('_serialize', ['answers']);
    }

    /**
     * View method
     *
     * @param string|null $id Answer id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $answer = $this->Answers->get($id, [
            'contain' => ['Customers', 'Questions', 'Options']
        ]);
        $this->set('answer', $answer);
        $this->set('_serialize', ['answer']);
    }

    /**
     * @apiDescription Answers Add
     * @apiUrl http://www.business.zakoopi.com/api/answers/add.json
     * @apiRequestType POST method
     * @apiRequestData {"customer_id":CustomerID,"answers":json,"final"}
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"code": "0/1","result":"<entity>"}}
     */
    public function save()
    {
        $d = $this->request->data;
        $customer_id = $d['customer_id'];
        $d = json_decode($d['answers'], true);
        file_put_contents(WWW_ROOT.'rq.txt', print_r($d,true));
        $tstamp = time();
        foreach($d['final'] as $ans){
            $ansData['customers_id'] = $customer_id;
            $ansData['question_id'] = $ans['ques_id'];
            $ansData['option_id'] = 0;
            $ansData['answer_type'] = $ans['ques_type'];
            $ansData['answer'] = $ans['option'];
            $ansData['soft_deleted'] = 0;
            $ansData['status'] = 1;
            $ansData['created'] = $tstamp;
            $ent = $this->Answers->newEntity($ansData);
            $this->Answers->save($ent);
        }
        
//        exit;
//        $answer = $this->Answers->newEntity();
//        if ($this->request->is('post')) {
//            $answer = $this->Answers->patchEntity($answer, $this->request->data);
//            if ($this->Answers->save($answer)) {
//                $this->Flash->success(__('The answer has been saved.'));
//                return $this->redirect(['action' => 'index']);
//            } else {
//                $this->Flash->error(__('The answer could not be saved. Please, try again.'));
//            }
//        }
//        $users = $this->Answers->Users->find('list', ['limit' => 200]);
//        $questions = $this->Answers->Questions->find('list', ['limit' => 200]);
//        $options = $this->Answers->Options->find('list', ['limit' => 200]);
//        $this->set(compact('answer', 'users', 'questions', 'options'));
//        $this->set('_serialize', ['answer']);
        $this->set('result',1);
        $this->set('_serialize', ['result']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Answer id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $answer = $this->Answers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $answer = $this->Answers->patchEntity($answer, $this->request->data);
            if ($this->Answers->save($answer)) {
                $this->Flash->success(__('The answer has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The answer could not be saved. Please, try again.'));
            }
        }
        $users = $this->Answers->Customers->find('list', ['limit' => 200]);
        $questions = $this->Answers->Questions->find('list', ['limit' => 200]);
        $options = $this->Answers->Options->find('list', ['limit' => 200]);
        $this->set(compact('answer', 'users', 'questions', 'options'));
        $this->set('_serialize', ['answer']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Answer id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $answer = $this->Answers->get($id);
        if ($this->Answers->delete($answer)) {
            $this->Flash->success(__('The answer has been deleted.'));
        } else {
            $this->Flash->error(__('The answer could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
