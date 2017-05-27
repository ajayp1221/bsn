<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Templatemessages Controller
 *
 * @property \App\Model\Table\TemplatemessagesTable $Templatemessages
 */
class TemplatemessagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $result = $this->Templatemessages->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
        ]);
        $result = new \Cake\Collection\Collection($result);
        $result = $result->groupBy('type');
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
    }

    /**
     * View method
     *
     * @param string|null $id Templatemessage id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $templatemessage = $this->Templatemessages->get($id, [
            'contain' => ['Stores']
        ]);

        $this->set('templatemessage', $templatemessage);
        $this->set('_serialize', ['templatemessage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post', 'put']);
        $templatemessage = $this->Templatemessages->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'type' => "welcome-message"
        ])->first();
        $templatemessagerepeat = $this->Templatemessages->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'type' => "welcome-message-repeat"
        ])->first();
        $templatemessagetext = $this->Templatemessages->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'type' => "welcome-message-screen-text"
        ])->first();
        $discountonnextvisit = $this->Templatemessages->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'type' => "discount-on-next-visit"
        ])->first();
        $this->request->data['store_id'] = $this->_appSession->read('App.selectedStoreID');
        $this->request->data['status'] = 1;
        if($templatemessage){
            $this->Templatemessages->updateAll(['message' => $this->request->data['welcome-message']], ['id' => $templatemessage->id]);
        }else{
            $this->request->data['type'] = 'welcome-message';
            $this->request->data['message'] = $this->request->data['welcome-message'];
            $templatemessageSave = $this->Templatemessages->newEntity($this->request->data);
            $this->Templatemessages->save($templatemessageSave);
        }
        if($templatemessagerepeat){
            $this->Templatemessages->updateAll(['message' => $this->request->data['welcome-message-repeat']], ['id' => $templatemessagerepeat->id]);
        }else{
            $this->request->data['type'] = 'welcome-message-repeat';
            $this->request->data['message'] = $this->request->data['welcome-message-repeat'];
            $templatemessagerepeatSave = $this->Templatemessages->newEntity($this->request->data);
            $this->Templatemessages->save($templatemessagerepeatSave);
        }
        if($templatemessagetext){
            $this->Templatemessages->updateAll(['message' => $this->request->data['welcome-message-screen-text']], ['id' => $templatemessagetext->id]);
        }else{
            $this->request->data['type'] = 'welcome-message-screen-text';
            $this->request->data['message'] = $this->request->data['welcome-message-screen-text'];
            $templatemessagetextSave = $this->Templatemessages->newEntity($this->request->data);
            $this->Templatemessages->save($templatemessagetextSave);
        }
        if($discountonnextvisit){
            $this->Templatemessages->updateAll(['message' => $this->request->data['discount-on-next-visit']], ['id' => $discountonnextvisit->id]);
        }else{
            $this->request->data['type'] = 'discount-on-next-visit';
            $this->request->data['message'] = $this->request->data['discount-on-next-visit'];
            $discountonnextvisitSave = $this->Templatemessages->newEntity($this->request->data);
            $this->Templatemessages->save($discountonnextvisitSave);
        }
        $result = [
            'error' => 0,
            'msg' => 'Data has been saved successfully'
        ];
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }
}
