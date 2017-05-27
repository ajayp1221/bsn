<?php
namespace App\Controller\Api\V1;

use App\Controller\Api\V1\AppController;

/**
 * Pushmessages Controller
 *
 * @property \App\Model\Table\PushmessagesTable $Pushmessages
 */
class PushmessagesController extends AppController
{

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }

        /**
     * @apiDescription Client Login
     * @apiUrl http://www.business.zakoopi.com/api/v1/pushmessages.json
     * @apiRequestType POST method
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"result":{"code": "0/1","pushmessages":"<entity>"}}
     */
    public function index()
    {
        $this->request->allowMethod(['post','put']);
        $storeId = $this->request->data['store_id'];
//        $storeId = $this->request->header('store-id');
//        $storeId = 2;
        $pushmessages = $this->Pushmessages->find()->where(['store_id' => $storeId,'status' => 1])->orderDesc('id')->limit(5)->toArray();
        if($pushmessages){
            $result = [
                'error' => 0,
                'msg' =>'success',
                'data' => $pushmessages
            ];
        }else{
            $result = [
                'error' => 1,
                'msg' => 'No notification found'
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }
}
