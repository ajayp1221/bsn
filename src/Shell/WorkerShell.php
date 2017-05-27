<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Log\Log;

class WorkerShell extends Shell {

    public $worker;
    
    public function main() {
        $this->worker = new \GearmanWorker();
        $this->worker->addServer();

        /*
         * Describe worker functions here
         */
        $this->worker->addFunction("nonrepeatsms", array($this, 'nonrepeatsms'));
        $this->worker->addFunction("nonrepeatemail", array($this, 'nonrepeatemail'));
        $this->worker->addFunction("cstcsvmsg", array($this, 'cstcsvmsg'));
        
//        $this->worker->addFunction("repeatsmsbday", array($this, 'repeatsmsbday'));
//        $this->worker->addFunction("repeatsmsanniversary", array($this, 'repeatsmsanniversary'));
//        $this->worker->addFunction("repeatemailbday", array($this, 'repeatemailbday'));
//        $this->worker->addFunction("repeatemailanniversary", array($this, 'repeatemailanniversary'));

        print "Waiting for job...\n";
        while ($this->worker->work()) {
            if ($this->worker->returnCode() != GEARMAN_SUCCESS) {
                echo "return_code: " . $this->worker->returnCode() . "\n";
                break;
            }
        }
    }

    public function initialize() {
        parent::initialize();
        $this->loadModel('Stores');
        $this->loadModel('Messages');
        $this->loadModel('Campaigns');
        $this->loadModel('Clients');

    }

    public function nonrepeatsms($job) {
        $data = unserialize($job->workload());
        $clientInfo = $this->Stores->find()
                        ->hydrate(false)
                        ->contain([
                            "Brands" => function($q) {
                                return $q->select(['id', 'client_id']);
                            },
                                    'Brands.Clients' => function($q) {
                                return $q->select(['id', 'sms_quantity', 'senderid', 'plivo_auth_id', 'plivo_auth_token','contact_no','name','low_balance_sms_pause']);
                            }])
                        ->select(['Stores.id', 'Stores.brand_id'])
                        ->where(['Stores.id' => $data['store_id'],'Stores.status' => 1])->first();
        $checkMobileNo = preg_match("^[789]\d{9}$^", $data['contact_no']);
        if ($checkMobileNo) {
            if ($clientInfo['brand']['client']['sms_quantity'] > 0) {
                $storeMessage = $data['message'];
                $storeMessage = str_replace('{{cstName}}', explode(" ", $data['cst_name'])[0], $storeMessage);
                $newmessage = $storeMessage;
                if ($data['promo_code']) {
                    $newmessage = $storeMessage . " CPN- " . $data['promo_code'];
                }
                $methods = new \App\Common\Methods();
                $apiResonpse = ['apiResponse' => ''];
                if($num = $methods->checkNum($data['contact_no'])){
                    $apiResonpse = $methods->smsgshupCampaign(
                            $num, $newmessage, $clientInfo['brand']['client']['id'], $clientInfo['brand']['client']['senderid'], 
                            "SMS Campaigns",
                            0,
                            $data['store_id']
                    );
                }

                $externalID = explode("|", $apiResonpse['apiResponse']);
                $externalID = trim($externalID[2]);
                $apijsonResonpse = (json_encode($apiResonpse));
                $this->Messages->updateAll(
                        [
                    'status' => 0,
                    'api_key' => $apiResonpse['status'],
                    'api_response' => $apijsonResonpse,
                    'cost_multiplier' => @$apiResonpse['response']['units'],
                    'external_msgid' => $externalID
                        ], [
                    'id' => $data['message_id']
                        ]
                );
            }elseif($clientInfo['brand']['client']['low_balance_sms_pause'] == 0){
                $cmp_id = $data['campaign_id'];
                $cmp = $this->Campaigns->get($cmp_id);
                $cmp->status = 2;                
                $this->Campaigns->save($cmp);
                $this->Clients->updateAll([
                    'low_balance_sms_pause' => 1
                ],[
                   'id'=> $clientInfo['brand']['client']['id']
                ]);
                $smsBalanceMsg = "Hi. All your SMS campaigns have been paused due to insufficient SMS balance. Pls recharge immediately & restart campaigns. Team Zakoopi";
                $methods = new \App\Common\Methods();
                $methods->smsgshupCampaign($clientInfo['brand']['client']['contact_no'], $smsBalanceMsg);
            }
        }
        return 'done';
    }
    
    public function cstcsvmsg($job){
        $data = unserialize($job->workload());
                
        $checkMobileNo = preg_match("^[789]\d{9}$^", $data['contact_no']);
        if ($checkMobileNo) {
            $methods = new \App\Common\Methods();
            $methods->smsgshup($data['contact_no'], $data['message'], $data['client_id'], $data['sender_id'], "New Customer(FROM CSV) SMS", $data['customer_id'], $data['store_id']);
        }
        return 'done';
    }

    public function nonrepeatemail($job) {
        $data = unserialize($job->workload());
        
        $clientInfo = $this->Stores->find()
                    ->hydrate(false)
                    ->contain([
                        "Brands" => function($q){return $q->select(['id','client_id']);},
                        'Brands.Clients' => function($q){return $q->select(['id','email_left','email_sent','contact_no','name']);}])
                    ->select(['Stores.id','Stores.brand_id'])
                    ->where(['Stores.id' => $data['store_id'],'Stores.status' => 1])->first();
        $verifyEmail = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        if($verifyEmail){
            if($clientInfo['brand']['client']['email_left']>0){
                $storeMessage = $data['message'];
                $storeMessage = str_replace('{{cstName}}', ($data['cst_name']), $storeMessage);
                $newmessage = $storeMessage;
                if($data['promo_code']){
                    $newmessage = $storeMessage." CPN- ".$data['promo_code'];
                }
                $methods = new \App\Common\Methods();
                $apiResonpse = $methods->sendemail(
                        $data['email'],
                        $newmessage,
                        $clientInfo['brand']['client_id'],
                        $data['subject'],
                        $data['store_id']
                    );
                $apijsonResonpse = (json_encode($apiResonpse));
                $this->Messages->updateAll(
                        [
                            'status' => 0,
                            'api_key' => 202,
                            'api_response' => $apijsonResonpse
                        ],
                        [
                            'id' => $data['message_id']
                        ]
                );
            }elseif($clientInfo['brand']['client']['low_balance_email_pause'] == 0){
                $cmp_id = $data['campaign_id'];
                $cmp = $this->Campaigns->get($cmp_id);
                $cmp->status = 2;                
                $this->Campaigns->save($cmp);
                $this->Clients->updateAll([
                    'low_balance_email_pause' => 1
                ],[
                   'id'=> $clientInfo['brand']['client']['id']
                ]);
                $smsBalanceMsg = "Hi. All your Email campaigns have been paused due to insufficient Email balance. Pls recharge immediately & restart campaigns. Team Zakoopi";
                $methods = new \App\Common\Methods();
                $methods->smsgshupCampaign($clientInfo['brand']['client']['contact_no'], $smsBalanceMsg);
            }
        }
        return 'done';
    }

    public function repeatsmsbday($job) {
        $data = $job->workload();
        print_r($data);
        return 'done';
    }

    public function repeatsmsanniversary($job) {
        $data = $job->workload();
        print_r($data);
        return 'done';
    }

    public function repeatemailbday($job) {
        $data = $job->workload();
        print_r($data);
        return 'done';
    }

    public function repeatemailanniversary($job) {
        $data = $job->workload();
        print_r($data);
        return 'done';
    }

}
        