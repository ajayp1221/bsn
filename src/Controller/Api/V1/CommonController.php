<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Api\V1;
use App\Controller\Api\V1\AppController;
/**
 * Description of CommonController
 *
 * @author Himanshu
 */
class CommonController extends AppController{
    
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['checkClient']);
    }
    
    /**
     * @apiDescription Client Login
     * @apiUrl http://www.business.zakoopi.com/api/Common/check-client.json
     * @apiRequestType POST method
     * @apiRequestData {"username":Email,"password":"Password"}
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"code": "0/1","result":"<entity>"}}
     */
    public function checkClient(){
        $tbl = \Cake\ORM\TableRegistry::get('Clients');
        $dHash = new \Cake\Auth\DefaultPasswordHasher();
        $user = $tbl->find()->where([
            'Clients.email' => $this->request->data['username'],
        ])->first();
        if($user){
            if($dHash->check($this->request->data['password'], $user->password)){
                $clientId = $user->id;
                $max_device_limit = $user->max_device_limit;
                $tblDevices = \Cake\ORM\TableRegistry::get('Devices');
                $devices = $tblDevices->find()->where([
                    'client_id' => $clientId,
                    'device_uuid' => $this->request->header('device-id')
                ])->all();
                $totalDevices = $tblDevices->find()->where([
                    'client_id' => $clientId,
                ])->all();
                if(count($devices->toArray()) == 0 && count($totalDevices->toArray()) < $max_device_limit){
                    $ent = $tblDevices->newEntity([
                        'client_id' => $clientId,
                        'device_uuid' => $this->request->header('device-id'),
                        'status' => 1,
                        'added_on' => time()
                    ]);
                    $tblDevices->save($ent);
                }elseif(count($devices->toArray()) == 0){
                    $clientId = 0; // Block Login
                }
            }else{
                $clientId = 0;
            }
        }else{
            $clientId = 0;
        }
        $this->set('clientId', $clientId);
        $this->set('_serialize', ['clientId']);
    }
    /**
     * @apiDescription Client Stores
     * @apiUrl http://www.business.zakoopi.com/api/Common/first-start.json
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"result":"<entity>"}}
     */
    
    public function firstStart(){
        $brandsTbl = \Cake\ORM\TableRegistry::get('Brands');
        $result = $brandsTbl->find()->contain(['Stores'])->where([
            'client_id' => $this->request->header('client-id')
        ])->all();

        $this->set('data', $result );
        $this->set('_serialize', ['data']);
    }
    
    /**
     * @apiDescription Check Customer Visits
     * @apiUrl http://www.business.zakoopi.com/api/Common/check-customer.json
     * @apiRequestType POST method
     * @apiRequestData {"contact_no":Mobile Number}
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"result":"<entity>"}}
     */
    
    public function checkCustomer(){
        $this->request->allowMethod('post');
        $customersTbl = \Cake\ORM\TableRegistry::get('Customers');
        $result = $customersTbl->find()->where([
            'Customers.contact_no' => $this->request->data['contact_no'],
            'Customers.store_id' =>  $this->request->header('store-id')
        ])->first();
        
        $clientTbl = \Cake\ORM\TableRegistry::get('Clients');
        $client = $clientTbl->get($this->request->header('client-id'));
        $welcomeMsgTbl = \Cake\ORM\TableRegistry::get('Welcomemsgs');
        
        $templatemessageTbl = \Cake\ORM\TableRegistry::get('Templatemessages');
        $templateMessage = $templatemessageTbl->find()
                ->where(['type' => 'welcome-message','store_id' =>  $this->request->header('store-id')])
                ->first();
        $templateMessageRepeat = $templatemessageTbl->find()
                ->where(['type' => 'welcome-message-repeat','store_id' =>  $this->request->header('store-id')])
                ->first();
        $newmessage = $templateMessage['message'];
        if($result){
            $newFlag = "existing";
            if($templateMessageRepeat){
                $newmessage = $templateMessageRepeat['message'];
            }
            $newmessage = str_replace('{{cstName}}', @$result->name, $newmessage);
        }else{
            $newFlag = "new";
//            $newmessage = str_replace('{{cstName}}', "customer", $newmessage) ." To unsubscribe SMS USUBZK". str_pad($this->request->header('store-id'), 4, "0", STR_PAD_LEFT) ." to 9220092200";
            $newmessage = str_replace('{{cstName}}', "customer", $newmessage);
        }
        
        
        if($result){
            $tblLogs = \Cake\ORM\TableRegistry::get('CustomerVisits');
            $cnt = $tblLogs->find()->where([
                'CustomerVisits.customer_id' => $result->id,
                'CustomerVisits.store_id' => $this->request->header('store-id'),
            ])->all()->count();
            $ent = $tblLogs->newEntity([
                'customer_id' => $result->id,
                'store_id' => $this->request->header('store-id'),
                'came_at' => time(),
                'visits_till_now' => $cnt + 1
            ]);
            $tblLogs->save($ent);
        }else{
            $nCst = $customersTbl->newEntity([
               'contact_no' => $this->request->data['contact_no'],
               'added_by' => 'MOBILE-APP',
               'store_id' => $this->request->header('store-id')
            ]);
            $result = $customersTbl->save($nCst);
        }
        
        $methods = new \App\Common\Methods();
        if($client->sms_quantity>0){
            if($num = $methods->checkNum($this->request->data['contact_no'])){
                $apiResonpse = $methods->smsgshup(
                    $num,
                    $newmessage,
                    $client->id,
                    $client->senderid,
                    "Customer Registrations (MOBILE-APP)",
                    $result->id,
                    $this->request->header('store-id')
                );
            }
        }
        $welEnt = $welcomeMsgTbl->newEntity([
            "customer_id" => $result->id,
            "type" => $newFlag,
            "created" => time(),
            "store_id" => $this->request->header('store-id'),
            "cost_multiplier" => @$apiResonpse['response']['units']
        ]);
        $welcomeMsgTbl->save($welEnt);
        
        $this->set('data', $result);
        $this->set('_serialize', ['data']);
    }
    
    /**
     * @apiDescription Add New Or Update Customer
     * @apiUrl http://www.business.zakoopi.com/api/Common/add-update-customer.json
     * @apiRequestType POST method
     * @apiRequestData {"name":Customer Name,"store_id":Store ID,"contact_no":Mobile Number,"gender":0/1,"dob":Customer DOB,"id":Customer ID}
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"result":"<entity>"}}
     */
    
    public function addUpdateCustomer(){
        $this->request->allowMethod('post');
        $d = $this->request->data;
        $tbl = \Cake\ORM\TableRegistry::get('Customers');
        $d['status'] = 1;
        $d['soft_deleted'] = 0;
        $tm = new \Cake\I18n\Time($d['dob']);
        $tm2 = new \Cake\I18n\Time();
        $a = $tm->diff($tm2);
        $d['age'] = $a->y;
        $d['added_by'] = "MOBILE-APP";
        $ent = $tbl->find()->where([
            'Customers.contact_no'=>$d['contact_no']
        ])->first();
        if($ent){
            $ent = $tbl->patchEntity($ent, $d);
        }else{
            $ent = $tbl->newEntity($d);
            $methods = new \App\Common\Methods();
            $templatemessageTbl = \Cake\ORM\TableRegistry::get('Templatemessages');
            $templateMessage = $templatemessageTbl->find()->where(['type' => 'welcome-message','store_id' => $d['store_id']])->first();
            $newmessage = $templateMessage['message'];
            
            $newmessage = str_replace('{{cstName}}', $d['name'], $newmessage);
            $this->loadModel('Clients');
            $clientInfo = $this->Clients->find()->select(['id','sms_quantity','senderid'])->where(['id'=> $this->request->header('client-id')])->first();
            if($clientInfo->sms_quantity>0){
                if($num = $methods->checkNum($d['contact_no'])){
                $apiResonpse = $methods->smsgshup(
                        $num,
//                        $newmessage." To unsubscribe SMS USUBZK". str_pad($d['store_id'], 4, "0", STR_PAD_LEFT) ." to 9220092200",
                        $newmessage,
                        $this->request->header('client-id'),
                        $clientInfo->senderid,
                        "Customer Registrations (MOBILE-APP)",
                        0,
                        $d['store_id']
                    );
                }
            }
        }
        $res = 0;
        if($customer = $tbl->save($ent)){
            $res = 1;
        }
        file_put_contents(WWW_ROOT.'rq.txt', print_r($ent,true));
      	$this->set('data', ['success' => $res]);
        $this->set('_serialize', ['data']);

    }

    
    /**
     * @apiDescription Client Stores
     * @apiUrl http://www.business.zakoopi.com/api/Common/logout.json
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"result":"<entity>"}}
     */
    
    public function logout(){
        $old = $this->Auth->user();
        $this->Auth->logout();
        $this->response->header('HTTP/1.1 401 Unauthorized', true);
        $this->set('data', [
            'old'=>$old,
            'current'=>$this->Auth->user()
        ] );
        $this->set('_serialize', ['data']);
    }
    
    public function setbuyertype(){
        $d = $this->request->data;
        $session = $this->request->session();
        $session->write('Api.customer_type', $d['customer_type']);
        $this->set("result",$d['customer_type']);
        $this->set("_serialize", ['result']);
    }
    
    /**
     * @apiDescription Add New Or Update Customer Specific Stores(Studio De Royale)
     * @apiUrl http://www.business.zakoopi.com/api/Common/add-update-customer2.json
     * @apiRequestType POST method
     * @apiRequestData {"name":Customer Name,"store_id":Store ID,"contact_no":Mobile Number,"gender":0/1,"dob":Customer DOB,"id":Customer ID}
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"result":"<entity>"}}
     */
    public function addUpdateCustomer2(){
        $this->request->allowMethod('post');
        $d = $this->request->data;
        $tbl = \Cake\ORM\TableRegistry::get('Customers');
        $d['status'] = 1;
        $d['soft_deleted'] = 0;
        $tm = new \Cake\I18n\Time($d['dob']);
        $tm2 = new \Cake\I18n\Time();
        $a = $tm->diff($tm2);
        $d['age'] = $a->y;
        $d['added_by'] = "MOBILE-APP";
        $ent = $tbl->find()->where([
            'Customers.contact_no'=>$d['contact_no']
        ])->first();
        if($ent){
            $ent = $tbl->patchEntity($ent, $d);
        }else{
            $ent = $tbl->newEntity($d);
            $methods = new \App\Common\Methods();
            $templatemessageTbl = \Cake\ORM\TableRegistry::get('Templatemessages');
            $templateMessage = $templatemessageTbl->find()->where(['type' => 'welcome-message','store_id' => $d['store_id']])->first();
            $newmessage = $templateMessage['message'];
            
            $newmessage = str_replace('{{cstName}}', $d['name'], $newmessage);
            $this->loadModel('Clients');
            $clientInfo = $this->Clients->find()->select(['id','sms_quantity','senderid'])->where(['id'=> $this->request->header('client-id')])->first();
            if($clientInfo->sms_quantity>0){
                if($num = $methods->checkNum($d['contact_no'])){
                    $apiResonpse = $methods->smsgshup(
                        $num,
//                        $newmessage." To unsubscribe SMS USUBZK". str_pad($d['store_id'], 4, "0", STR_PAD_LEFT) ." to 9220092200",
                            $newmessage,
                        $this->request->header('client-id'),
                        $clientInfo->senderid,
                        "Customer Registrations (MOBILE-APP)",
                        0,
                        $d['store_id']
                    );
                }
            }
        }
        $res = 0;
        if($customer = $tbl->save($ent)){
            $res = 1;
        }
//        file_put_contents(WWW_ROOT.'rq.txt', print_r($ent,true));
      	$this->set('data', ['success' => $res]);
        $this->set('_serialize', ['data']);
    }
}
