<?php
namespace App\Controller\Api\V1;

use App\Controller\Api\V1\AppController;

/**
 * Notifications Controller
 *
 * @property \App\Model\Table\NotificationsTable $Notifications
 */
class NotificationsController extends AppController
{

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }

    /**
     * @apiDescription Notification Add
     * @apiUrl http://www.business.zakoopi.com/api/notifications/add.json
     * @apiRequestType POST method
     * @apiRequestData {"type":IOS,"store_slug": Store-Slug,"device_token": Mobile-Device-Token: ,"deviceid":Mobile-Device-Id}
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"code": "0/1","result":"<entity>"}}
     */
    public function add(){
        if($this->request->is(['post','put'])){
            file_put_contents(WWW_ROOT.'rq.txt', print_r($this->request->data,true));
            $check = $this->Notifications->find()
                    ->where([
                        'type' => $this->request->data['type'],
                        'store_slug' => $this->request->data['store_slug'],
                        'device_token' => $this->request->data['device_token']
                    ])
                    ->first();
            if($check){
                $response['msg'] = 'Record has been allready saved';
                $response['error'] = '2';
            }else{
                $this->request->data['status'] = 1;
                $pushmessages = $this->Notifications->newEntity($this->request->data);
                if($this->Notifications->save($pushmessages)){
                    $response['error'] = "0";
                    $response['msg'] = 'Record has been saved successfully';
                }else{
                    $response['error'] = "1";
                    $response['msg'] = 'something went wrong please try again';
                }
            }
            $this->set(compact('response'));
            $this->set('_serialize', ['response']);
        }
    }
}
