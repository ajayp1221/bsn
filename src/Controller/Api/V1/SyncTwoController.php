<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Api\V1;

use App\Controller\Api\V1\AppController;

/**
 * Description of SyncTwoController
 *
 * @author Himanshu
 */
class SyncTwoController extends AppController{
    
    private $_customer_id = null;
    private $_store_id = null;
    private $_client_id = null;

    private function getCustomer($contact_no = null,$store_id = null){
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
        return $cstTbl->find()->where([
            "Customers.contact_no" => $contact_no,
            "Customers.store_id" => $this->_store_id
        ])->first();
    }
    
    private function saveCustomer($data){
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
        $ent = $cstTbl->newEntity([
            "contact_no" => $data['contact_no'],
            "email" => $data['email'],
            "name" => $data['name'],
            "gender" => $data['gender'],
            "dob" => $data['dob'],
            "doa" => $data['doa'],
            "kids_info" => json_encode($data['kids_info']),
            "spouse_name" => $data['spouse_name'],
            "spouse_date" => $data['spouse_date'],
            "opt_in" => 1,
            "status" => 1,
            "soft_deleted" => 0,
//            "created" => \Cake\I18n\Time::createFromTimestamp((int)((int)($data['recordedat']) / 1000)),
            "added_by" => 'MOBILE-APP|SYNC',
            "store_id" => $data['store_id']
            
        ]);
        
        $welcomeMsgTbl = \Cake\ORM\TableRegistry::get('Welcomemsgs');
        
        $templatemessageTbl = \Cake\ORM\TableRegistry::get('Templatemessages');
        $templateMessage = $templatemessageTbl->find()
                ->where(['type' => 'welcome-message','store_id' =>  $this->_store_id])
                ->first();
        $newmessage = $templateMessage->message;
        $newmessage = str_replace('{{cstName}}', "customer", $newmessage);
        $methods = new \App\Common\Methods();
        $resp = $cstTbl->save($ent);
        if($num = $methods->checkNum($data['contact_no'])){
            $apiResonpse = $methods->smsgshup(
                                $num,
//                                $newmessage." To unsubscribe SMS USUBZK". str_pad($this->_store_id, 4, "0", STR_PAD_LEFT) ." to 9220092200",
                                $newmessage,
                                null,
                                $this->_client_id,
                                "Customer Registrations (MOBILE-APP)",
                                $resp->id,
                                $this->_store_id
//                                $this->request->header('store-id') == 6 ? "KRASNS" : ""
//                                    $this->request->header('client-id')
                            );
        
        }
        
//        if($ent->errors()){
//            debug($ent);
//        }
        return $resp;
    }
    private function updateCustomer($data,$ent){
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
        $ent->name = $data['name'];
        $ent->gender = $data['gender'];
        $ent->email = $data['email'];
        $ent->dob = $data['dob'];
        $ent->doa = $data['doa'];
        $ent->kids_info = @json_encode(@$data['kids_info']);
        $ent->spouse_name = $data['spouse_name'];
        $ent->spouse_date = $data['spouse_date'];
        
        $welcomeMsgTbl = \Cake\ORM\TableRegistry::get('Welcomemsgs');
        
        $templatemessageTbl = \Cake\ORM\TableRegistry::get('Templatemessages');
        $templateMessageRepeat = $templatemessageTbl->find()
                ->where(['type' => 'welcome-message-repeat','store_id' =>  $this->_store_id])
                ->first();
        $newmessage = $templateMessageRepeat['message'];       
        $newmessage = str_replace('{{cstName}}', $data['name'], $newmessage);
        
        $methods = new \App\Common\Methods();
        $resp = $cstTbl->save($ent);
        if($num = $methods->checkNum($ent->contact_no)){
            $apiResonpse = $methods->smsgshup(
                                $num,
                                $newmessage,
                                null,
                                $this->_client_id,
                                "Welcome Repeat Customer",
                                $resp->id,
                                $this->_store_id
//                                $this->request->header('store-id') == 6 ? "KRASNS" : ""
//                                    $this->request->header('client-id')
                            );
        }
       
//        $ent->created = \Cake\I18n\Time::createFromTimestamp((int)((int)($data['recordedat']) / 1000));
        return $resp;
    }


    private function saveAnswers($data,$cstId = null){
        $ansTbl = \Cake\ORM\TableRegistry::get('Answers');
        $saved = [];
        foreach($data as $answer){
            $ent = [];
            $ent['customers_id'] = $cstId;
            $ent['question_id'] = $answer['ques_id'];
            $ent['answer_type'] = $answer['ques_type'];
            $ent['answer'] = $answer['option'];
            $ent['status'] = 1;
            $ent['soft_deleted'] = 0;
            $ent['is_published'] = 0;
            $entity = $ansTbl->newEntity($ent);
            $saved[] = $ansTbl->save($entity);
        }
        return $saved;
    }
    
    private function savePricedata($data,$cstId = null,$store_id = null){
        $prchTbl = \Cake\ORM\TableRegistry::get('Purchases');
        $saved = [];
        foreach($data as $record){
            $entity = $prchTbl->newEntity([
                "store_id" => $store_id,
                "product_id" => $record['product_id'],
                "customer_id" => $cstId,
                "product_name" => $record['product_name'],
                "qty" => 1,
                "price" => $record['product_price'],
                "sold_on" => time(),
            ]);
            $saved[] = $prchTbl->save($entity);
            $saved[] = $entity;
        }
        return $saved;
    }
    
    
    private function recommendCustomers($data,$cstId = null,$store_id = null){
        $custbl = \Cake\ORM\TableRegistry::get('Customers');
        $saved = [];
        $storeTbl = \Cake\ORM\TableRegistry::get('Stores');
        $clientInfo = $storeTbl->find()->hydrate(false)->select(['id'])
                ->contain([
                    'Brands' => function($q){
                        return $q->select(['Brands.id','Brands.client_id']);
                    },
                    'Brands.Clients' => function($q){
                        return $q->select(['Clients.id','Clients.senderid','Clients.sms_quantity']) ;
                    }
                ])
                ->where(['Stores.id' => $this->_store_id])->first();
                $campaignsTbl = \Cake\ORM\TableRegistry::get('Campaigns');
                $campaigns = $campaignsTbl->find()->contain(['Stores'])->where(['store_id' => $this->_store_id, 'campaign_type' => 'refered'])->first();
                $newmessage = $campaigns['message'];
                $city = explode("-", $campaigns->store->slug);
                $city = $city[(count($city) - 1)] == "ncr" ? "delhi-ncr" : $city[(count($city) - 1)];
                $googl = new \Sonrisa\Service\ShortLink\Google('AIzaSyAzC7nmB9Y2shrUBg2749_AYwOpBQhxGlY');
                $strUrl = "http://www.zakoopi.com/".$city."/".$campaigns->store->slug;
                $newmessage = str_replace('{{storeName}}', $campaigns->store->name, $newmessage);
                $newmessage = str_replace('{{ZakoopiStoreShortLink}}', $strUrl, $newmessage);
        foreach($data as $d){
            if($d['number']){
                $check = "";
                $entity = "";
                $check = $custbl->find()->where(['contact_no' => $d['number'],'store_id' => $store_id])->first();
                if(!$check){
                    $newmessage = str_replace('{{cstName}}', explode(" ", $d['name'])[0], $newmessage);
                    $newmessage = str_replace('{{frndName}}', explode(" ", $d['name'])[0], $newmessage);
                    if($clientInfo['brand']['client']['sms_quantity']>0){
                        $methods = new \App\Common\Methods();
                        $apiResonpse = ['apiResponse'=>''];
                        if($num = $methods->checkNum($d['number'])){
                            $apiResonpse = $methods->smsgshup(
                                $num,
                                $newmessage,
                                $clientInfo['brand']['client_id'],
                                $clientInfo['senderid'],
                                "Recommend SMSes (FRIEND)",
                                0,
                                $store_id,
                                $d['number']
                            );
                            $apijsonResonpse = (json_encode($apiResonpse));
                        }
                    }
                    $externalID = explode("|", $apiResonpse['apiResponse']);
                    $externalID = trim($externalID[2]); //'external_msgid' => $externalID
                    $entity = [
                            'contact_no' => $d['number'],
                            'name' => $d['name'],
                            'store_id' => $store_id,
                            'status' => 1,
                            'added_by' => "MOBILE-APP|SYNC",  
                            'refered_by' => $cstId
                        ];
                    $entity = $custbl->newEntity($entity);
                    $saved[] = $custbl->save($entity);
                }
            }
        }
        return $saved;
    }

    /**
     * 
     */
    public function androidSync(){
        $this->_store_id = $store_id = $this->request->header('store-id');
        $this->_client_id = $client_id = $this->request->header('client-id');
        $result = [
          "msg" => "All data has been synced...",
          "error" => 0
        ];
        $d = $this->request->data['sync'];
file_put_contents(WWW_ROOT."rq.txt",print_r($d,true));
//        $d = $this->getCustomer('8960799090',2);
//        $d = file_get_contents(WWW_ROOT."newjsonfile.json");
        $d = json_decode($d,true);
        $d = $d['result'];
        foreach($d as $block){
            $cst = $this->getCustomer($block['userdata']['contact_no'], $store_id);
            if(!$cst){
               $cst = $this->saveCustomer($block['userdata']);
            }else{
                $cst = $this->updateCustomer($block['userdata'], $cst);
            }
            $customer_id = $cst->id;
            //file_put_contents(WWW_ROOT."rq.txt",print_r($block,true));
            $ansResult = $this->saveAnswers($block['answerdata']['final'],$customer_id);
            $prchResult = $this->savePricedata($block['pricedata']['final'], $customer_id, $store_id);
            $recResult = $this->recommendCustomers($block['frienddata']['final'], $customer_id, $store_id);
//            pr($prchResult);
        }
//        exit;
        $this->set("result",$result);
        $this->set("_serialize",['result']);
    }
    
}
