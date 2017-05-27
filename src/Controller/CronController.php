<?php
namespace App\Controller;

use App\Controller\AppController;
use Abraham\TwitterOAuth\TwitterOAuth;

define("CONSUMER_KEY", "OFUAhLvVu8woKINv0rgIBJqPf");
define("CONSUMER_SECRET", "zdoQXG6cDhMrzOIYof64MjyAnujzStBdv5lWe69z3Ir10iMD8Q");

/**
 * Cron Controller
 *
 * @property \App\Model\Table\MessagesTable $Messages
 * @property \App\Model\Table\CampaignsTable $Campaigns
 * @property \App\Model\Table\ClientsTable $Clients
 * @property \App\Model\Table\CustomersAlbumsharesTable $CustomersAlbumshares
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\AlbumsharesTable $Albumshares
 * @property \App\Model\Table\StoresTable $Stores
 * @property \App\Model\Table\PushmessagesTable $Pushmessages
 * @property \App\Model\Table\NotificationsTable $Notifications
 */

class CronController extends AppController{
    
    public function beforeFilter(\Cake\Event\Event $event) {
         parent::beforeFilter($event);
         $this->Auth->allow();
     }
     
     public function sendLeadSms($name = "",$source = "JustDial",$number=null){
        if($number == null){
            return false;
        }
        
        $methods = new \App\Common\Methods();
        $msg =  "Hi ".$name.", in respect of your recent query on ".$source.", we at SDR would like to offer our services. Regards, 7838881003, https://goo.gl/JqtJ8R";
        $number = preg_replace("/(\\+91)/", "", $number);
//        $number = "8860641616";
        if($num = $methods->checkNum($number)){
            $justdRes = $methods->smsgshup($num, $msg, 11, "SROYAL","Leads (".$source.")");
            $this->loadModel('Customers');
            $checkCust = $this->Customers->find()->where(['store_id' => 16,'contact_no' => $num])->first();
            if(!$checkCust){
                $d = [
                    'contact_no' => $num,
                    'name' => $name,
                    'store_id' => 16,
                    'country_code' => '+91',
                    'added_by' => $source,
                    'opt_in' => '1',
                    'status' => '1'
                ];
                $d = $this->Customers->newEntity($d);
                $this->Customers->save($d);
//                $ms = "Hi ".$name." You have successfully registered at Studio De Royale. To unsubscribe, SMS USUBZK0016 to 9220092200 - SDR";
                $ms = "Hi ".$name." You have successfully registered at Studio De Royale.";
                $methods->smsgshup($num, $ms, 11, "SROYAL","Customer Registrations ($source)");
            }
            return $justdRes;
        }
    }

    public function jdimap($page = 1){
        \Cake\Core\Configure::write('debug', true);
        $mailbox = new \PhpImap\Mailbox('{imap.gmail.com:993/imap/ssl}SROYAL', 'demo@zakoopi.com', 'demo@1234', __DIR__);
        $mails = array();
        $mailsIds = $mailbox->searchMailbox('SUBJECT "justdial" UNSEEN' );
        if(!$mailsIds) {
            die('Mailbox is empty');
        }

        $data = [];
        require ROOT . '/vendor/niels/ganon/src/Ganon.php';
        foreach($mailsIds as $mailId){
            $mail = $mailbox->getMail($mailId);
            $html = str_get_dom($mail->textHtml);

            $xyz = $html('tbody', 1);
	    $xy = $xyz('tr',1);
            $dt['caller_name'] = $xy('td',1)->getPlainText();
            $xy = $xyz('tr',2);
            $dt['caller_requirment'] = $xy('td',1)->getPlainText();
            $xy = $xyz('tr',6);
            $dt['caller_phone'] = $xy('td',1)->getPlainText();
            $data[] = [
                "date" => $mail->date,
                "subject" => $mail->subject,
                "fromAddress" => $mail->fromAddress,
                //"textHtml" => trim($xyz),
                "data" => $dt
            ];
            $this->sendLeadSms($dt['caller_name'], "JustDial", $dt['caller_phone']);
        }
//        $this->set("data",$data);
//        $this->set("_serialize",['data']);
        exit;
    }
    
    public function jdservicesimap($page = 1){
        \Cake\Core\Configure::write('debug', true);
        $mailbox = new \PhpImap\Mailbox('{imap.gmail.com:993/imap/ssl}SROYAL', 'demo@zakoopi.com', 'demo@1234', __DIR__);
        $mails = array();
        $mailsIds = $mailbox->searchMailbox('FROM "just dial services" UNSEEN' );
        if(!$mailsIds) {
            die('Mailbox is empty');
        }

        $data = [];
        require ROOT . '/vendor/niels/ganon/src/Ganon.php';
        foreach($mailsIds as $mailId){
            $mail = $mailbox->getMail($mailId);
            if(preg_match("/VN Number/", $mail->subject)){
                continue;
            }
            $html = str_get_dom($mail->textHtml);

            $xyz = $html('table', 2);
	    $xy = $xyz('tr',1);
            $dt['caller_name'] = explode(" from", $xy('td',1)->getPlainText())[0];
            $xy = $xyz('tr',2);
            $dt['caller_requirment'] = $xy('td',1)->getPlainText();
            $xy = $xyz('tr',6);
            $dt['caller_phone'] = explode(", ", $xy('td',1)->getPlainText());
            if(strlen($dt['caller_phone'][0]) != 13){
                $xy = $xyz('tr',7);
                $dt['caller_phone'] = explode(", ", $xy('td',1)->getPlainText());
            }
            $data[] = [
                "date" => $mail->date,
                "subject" => $mail->subject,
                "fromAddress" => $mail->fromAddress,
                //"textHtml" => trim($xyz),
                "data" => $dt
            ];
            $this->sendLeadSms($dt['caller_name'], "JustDial", $dt['caller_phone'][0]);
        }
//        $this->set("data",$data);
//        $this->set("_serialize",['data']);
        exit;
    }
    
    public function metroimap($page = 1){
       
        \Cake\Core\Configure::write('debug', true);
        $mailbox = new \PhpImap\Mailbox('{imap.gmail.com:993/imap/ssl}SROYAL', 'demo@zakoopi.com', 'demo@1234', __DIR__);
        $mails = array();
        $mailsIds = $mailbox->searchMailbox('FROM "MatrimonyDirectory" UNSEEN' );
        if(!$mailsIds) {
            die('Mailbox is empty');
        }

        $data = [];
        require ROOT . '/vendor/niels/ganon/src/Ganon.php';
        foreach($mailsIds as $mailId){
            $mail = $mailbox->getMail($mailId);
            
            if(preg_match("/VN Number/", $mail->subject)){
                continue;
            }
//            pr($mail->textHtml); exit;
            $html = str_get_dom($mail->textHtml);

            $xyz = $html('table', 4);
	    $xy = $xyz('tr',0);
            $dt['caller_name'] = explode(": ",$xy('td',1)->getPlainText());
            $xy = $xyz('tr',1);
            $dt['caller_phone'] = explode(": ", $xy('td',1)->getPlainText());
            $data[] = [
                "date" => $mail->date,
                "subject" => $mail->subject,
                "fromAddress" => $mail->fromAddress,
                //"textHtml" => trim($xyz),
                "data" => $dt
            ];
            $this->sendLeadSms($dt['caller_name'][1], "MatrimonyDirectory", $dt['caller_phone'][1]);
        }
//        $this->set("data",$data);
//        $this->set("_serialize",['data']);
        exit;
    }

    public function shareposts(){
         $tbl = \Cake\ORM\TableRegistry::get('Socialshares');
         $tbl2 = \Cake\ORM\TableRegistry::get('SocialConnections');
         $results = $tbl->find()->where([
             'Socialshares.status' => 0
         ])->all();
         foreach($results as $r){
             $d1 = strtotime($r->schedule_date);
             $d2 = time();
             $c1 = new \Cake\I18n\Time($d1);
             $c2 = new \Cake\I18n\Time($d2);
             $c3 = $c1->diff($c2);
             if($c3->d < 2){

                 $connections = $tbl2->find()->where([
                     'SocialConnections.client_id' => $r->client_id
                 ]);
                 foreach($connections as $cn){
                     $x = $cn->model;
                     if($r->$x){
                         $path =  'https://s3-ap-southeast-1.amazonaws.com/bsn-zkp/uploads/socialPost/'.$r->image.'.jpg';//WWW_ROOT.'uploads'.DS.'socialPost'.DS.$r->image.'-640x640.jpg';
                         if($x == 'facebook'){
                             define("FB_ACCESS_TOKEN", $cn->access_token);
                             $res['facebook'] = $this->fbpost('https://s3-ap-southeast-1.amazonaws.com/bsn-zkp/uploads/socialPost/'.$r->image.'.jpg', $r->description,  explode(",", $cn->key),$r->image == "" ? false : true);
                         }
                         if($x == 'twitter'){
                             $accessToken = json_decode($cn->access_token,true);
                             define("TW_OAUTH_TOKEN", $accessToken['oauth_token']);
                             define("TW_OAUTH_SECRET", $accessToken['oauth_token_secret']);
                             $res['twitter'] = $this->twitterpost($path, $r->description); 
                         }
                     }
                 }
                 $r->status = 1;
                 $tbl->save($r);
             }
         }
         exit;
     }

    public function fbpost($filepath,$message,$ids, $img = false) {

        $accessToken = FB_ACCESS_TOKEN;
        
        $fb = new \Facebook\Facebook([
            'app_id' => '201877090144591',
            'app_secret' => '4fa0058a1cdf0cff694b9e62f530901f',
            'default_graph_version' => 'v2.2',
        ]);
        $helper = $fb->getRedirectLoginHelper();

        $linkData = [
//            'source' =>  $fb->fileToUpload($filepath),
//            'link' => $link,
            'url' => $filepath,
            'message' => $message,
        ];
        foreach ($ids as $i){
	    $accToken = $accessToken;
            if($i != 'me'){  // getting page access token
                try{
                    $pageAccessToken = $fb->get('/'.$i.'?fields=access_token', $accessToken);
                } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                    echo 'Graph returned an error: ' . $e->getMessage();
                    //exit;
                } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                    echo 'Facebook SDK returned an error: ' . $e->getMessage();
                    //exit;
                }
		pr($pageAccessToken->getDecodedBody() );
                $accToken = $pageAccessToken->getDecodedBody()['access_token'];
            }
            try {
                // Returns a `Facebook\FacebookResponse` object
                if($img){
                    $response = $fb->post('/'.$i.'/photos', $linkData, $accToken );
                }else{
                    $response = $fb->post('/'.$i.'/feed', $linkData, $accToken );
                }
            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                echo  'Graph returned an error: ' . $e->getMessage();
                echo '/'.$i.'/photos';
                //exit;
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                //exit;
            }
        }
        return $response;
    }
    
    /**
     * 
     * @param String $filename
     * @param String $caption
     * @return array
     */
    public function twitterpost($filename = NULL,$caption = "Testcaption"){
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, TW_OAUTH_TOKEN, TW_OAUTH_SECRET);
        $content = $connection->get('account/verify_credentials');
        $status_message = $caption;
        
        $key = $connection->upload('media/upload', array('media' => $filename));
        $mediaString = $key->media_id_string;
        
        if (isset($mediaString)) {
            $parameters = array(
                        'status' => $status_message,
                        'media_ids' => $mediaString,
                    );
            $result = $connection->post('statuses/update', $parameters);
        }else{
            $result = $connection->post('statuses/update', array('status' => $status_message));
        }
        return $result;
    }

    public function instagrampost($filename = NULL,$caption = "Testcaption") {
        /* Set the username and password of the account that you wish to post a photo to */
        $username = 'zakoopi';
        $password = 'dragonvrmxt2t@';
        /* Set the path to the file that you wish to post. */
        /* This must be jpeg format and it must be a perfect square */
//        $filename = WWW_ROOT . 'twitter_img' . DS . $img;
        /* Set the caption for the photo */
//        $caption = "Testcaption";
        /* Define the user agent */
        $agent = 'Instagram 6.21.2 Android (19/4.4.2; 480dpi; 1152x1920; Meizu; MX4; mx4; mt6595; en_US)';
        /* Define the GuID */
        $guid = $this->GenerateGuid();
        /* Set the devide ID */
        $device_id = "android-" . $guid;
        /* LOG IN */
        /* You must be logged in to the account that you wish to post a photo too */
        /* Set all of the parameters in the string, and then sign it with their API key using SHA-256 */
        $data = '{"device_id":"' . $device_id . '","guid":"' . $guid . '","username":"' . $username . '","password":"' . $password . '","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';
        $sig = $this->GenerateSignature($data);        
        $data = 'signed_body=' . $sig . '.' . urlencode($data) . '&ig_sig_key_version=6';
        $login = $this->SendRequest('accounts/login/', true, $data, $agent, false);
        if (strpos($login[1], "Sorry, an error occurred while processing this request.")) {
            $response[] = "Request failed, there's a chance that this proxy/ip is blocked";
        } else {
            if (empty($login[1])) {
                $response[] = "Empty response received from the server while trying to login";
            } else {
                /* Decode the array that is returned */
                $obj = @json_decode($login[1], true);
                if (empty($obj)) {
                    $response[] = "Could not decode the response: " . $body;
                } else {
                    /* Post the picture */
                    $data = $this->GetPostData($filename);
                    $post = $this->SendRequest('media/upload/', true, $data, $agent, true);
                    if (empty($post[1])) {
                        $response[] = "Empty response received from the server while trying to post the image";
                    } else {
                        /* Decode the response  */
                        $obj = @json_decode($post[1], true);
                        if (empty($obj)) {
                            $response[] = "Could not decode the response";
                        } else {
                            $status = $obj['status'];
                            if ($status == 'ok') {
                                /* Remove and line breaks from the caption */
                                $caption = preg_replace("/\r|\n/", "", $caption);
                                $media_id = $obj['media_id'];
                                $device_id = "android-" . $guid;
                                $data = '{"device_id":"' . $device_id . '","guid":"' . $guid . '","media_id":"' . $media_id . '","caption":"' . trim($caption) . '","device_timestamp":"' . time() . '","source_type":"5","filter_type":"0","extra":"{}","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';
                                $sig = $this->GenerateSignature($data);
                                $new_data = 'signed_body=' . $sig . '.' . urlencode($data) . '&ig_sig_key_version=4';
                                /* Now, configure the photo */
                                $conf = $this->SendRequest('media/configure/', true, $new_data, $agent, true);
                                if (empty($conf[1])) {
                                    $response[] = "Empty response received from the server while trying to configure the image";
                                } else {
                                    if (strpos($conf[1], "login_required")) {
                                        $response[] = "You are not logged in. There's a chance that the account is banned";
                                    } else {
                                        $obj = @json_decode($conf[1], true);
                                        $status = $obj['status'];
                                        if ($status != 'fail') {
                                            $response[] = "Success";
                                        } else {
                                            $response[] = 'Fail';
                                        }
                                    }
                                }
                            } else {
                                $response[] = "Status isn't okay";
                            }
                        }
                    }
                }
            }
        }
        return ['response' => $response[0],'obj'=>$obj];
    }

    function SendRequest($url, $post, $post_data, $user_agent, $cookies) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://instagram.com/api/v1/' . $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        if ($cookies) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) .'/cookies.txt');
        } else {
            curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) .'/cookies.txt');
        }
        $response = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array($http, $response);
    }

    function GenerateGuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    function GenerateSignature($data) {
        return hash_hmac('sha256', $data, '25eace5393646842f0d0c3fb2ac7d3cfa15c052436ee86b5406a8433f54d24a5');
    }

    function GetPostData($filename) {
        if (!$filename) {
            echo "The image doesn't exist " . $filename;
        } else {
            $post_data = array('device_timestamp' => time(),
                'photo' => '@' . $filename);
            return $post_data;
        }
    }
    
    public function checkplivosms() {
        require_once(ROOT . DS . 'webroot' . DS . 'plivo.php');
        $auth_id = "MAMZVKMDGXYWVLZME4YT";
        $auth_token = "ZjcxNjU1MTBhYjM5YzJiM2RmOWIxNDZiZTkzOTBk";
        $p = new \RestAPI($auth_id, $auth_token);  
        $params = array('record_id' => '1c5eaf2b-eaf1-4579-b442-f0c828318d18');
        $response = $p->get_message($params);
        debug($response);exit;
    }

    function reverse_created($task)
    {
        echo "CREATED: " . $task->jobHandle() . "\n";
    }

    function reverse_status($task)
    {
        echo "STATUS: " . $task->jobHandle() . " - " . $task->taskNumerator() . 
             "/" . $task->taskDenominator() . "\n";
    }

    function reverse_complete($task)
    {
        echo "C: " . $task->jobHandle() . ", " . $task->data() . "\n";
    }

    function reverse_fail($task)
    {
        echo "FAILED: " . $task->jobHandle() . "\n";
    }

    function reverse_data($task)
    {
        echo "DATA: " . $task->data() . "\n";
    }
    
    /*-------------------------------------ONE PLIVO MESSAGE------------------------------------ */
    
    public function nonrepeatsms(){
        $gmc= new \GearmanClient();
        $gmc->addServer();
        $gmc->setCompleteCallback([$this,"reverse_complete"]);
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $todayDate = date('Y/m/d H:00');
        $this->loadModel('Campaigns');
        $this->loadModel('Messages');
        $newCampaigns = $this->Campaigns->find()
                ->Contain([
                    'Messages' => function($q){return $q->select([
                                'Messages.promo_code','Messages.customer_id','Messages.status','Messages.campaign_id','Messages.store_id','Messages.id'
                            ]) ->where(['Messages.status' => 1]);},
                    'Messages.Customers' => function($q){return $q->where(['Customers.contact_no !=' => '','Customers.opt_in' => 1])->select(['Customers.name','Customers.contact_no']);},
                ])
                ->select([
                    'Campaigns.id','Campaigns.repeat','Campaigns.status','Campaigns.message', 'Campaigns.campaign_type', 'Campaigns.send_date'
                ])
                ->hydrate(false)
                ->where([
                    'Campaigns.repeat' => 0,
                    'Campaigns.status' => 1,
                    'Campaigns.campaign_type' => "sms",
                    'Campaigns.send_date' => $todayDate
                ])
                ->toArray();
            $this->loadModel('Stores');
        foreach($newCampaigns as $newCampaign){
            $check = $this->Campaigns->find()->where(['status' => 1])->first();
            if($check){
                $this->Campaigns->updateAll(['status' => 0],['id' => $newCampaign['id']]);
                $smsCredit = 0;
                foreach($newCampaign['messages'] as $message){
                    /**
                     * Send Task to Workers
                     */
                    $dt = [
                        "message"=>$newCampaign['message'],
                        'store_id'=>$message['store_id'],
                        'contact_no' => $message['customer']['contact_no'],
                        'promo_code' => $message['promo_code'],
                        'cst_name' => $message['customer']['name'],
                        'message_id' => $message['id'],
                        'campaign_id' => $newCampaign['id']
                    ];
                    $task= $gmc->addTaskBackground("nonrepeatsms", serialize($dt),null);
                    continue;
                }
            }
        }
        $gmc->runTasks();
        exit;
    }
    
    /*-------------------------------------BirthDay PLIVO MESSAGE------------------------------------ */
    
    public function repeatsmsbday(){
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $todayDate = date('m/d/Y');
        $currentTime = date('H');
        $this->loadModel('Campaigns');
        $this->loadModel('Customers');
        $this->loadModel('Messages');
        $newCampaigns = $this->Campaigns->find()
                ->select([
                    'Campaigns.id','Campaigns.repeat','Campaigns.status','Campaigns.message', 'Campaigns.campaign_type', 'Campaigns.send_before_day',
                    'Campaigns.embedcode','Campaigns.store_id','Campaigns.send_at'
                ])
                ->hydrate(false)
                ->where([
                    'Campaigns.repeat' => 1,
                    'Campaigns.status' => 1,
                    'Campaigns.campaign_type' => "sms",
                    'Campaigns.repeat_type' => "birthday"
                ])
                ->toArray();
        $this->loadModel('Stores');        
        foreach($newCampaigns as $newCampaign){
            if($currentTime == explode(":", @$newCampaign['send_at'])[0]){
                $addDays = $newCampaign['send_before_day'];
                $Date =  date("m/d");
                $month = date('m', strtotime($Date. "  + $addDays days"));
                $date = date('d', strtotime($Date. "  + $addDays days"));
                $customerLists = $this->Customers->find()
                        ->hydrate(FALSE)
                        ->where([
                            'store_id' => $newCampaign['store_id'],
                            'contact_no !=' => '',
                            "DAY(str_to_date(`dob`,'%m/%d/%Y'))" => $date, "MONTH(str_to_date(`dob`,'%m/%d/%Y'))" => $month
                        ])
                        ->select(['id', 'name', 'contact_no'])
                        ->toArray();
                foreach($customerLists as $customerList){
                    $clientInfo = $this->Stores->find()
                                    ->hydrate(false)
                                    ->contain([
                                        "Brands" => function($q){return $q->select(['id','client_id']);},
                                        'Brands.Clients' => function($q){
                                            return $q->select(['id','sms_quantity','senderid','plivo_auth_id','plivo_auth_token']);
                                        }])
                                    ->select(['Stores.id','Stores.brand_id'])
                                    ->where(['Stores.id' => $newCampaign['store_id'],'Stores.status' => 1])->first();
                    $checkMobileNo = preg_match("^[789]\d{9}$^", $customerList['contact_no']);
                    if($checkMobileNo){
                        if($clientInfo['brand']['client']['sms_quantity']>0){
                            $message = $this->Messages->newEntity();
                            $promoCode = "";
                            $storeMessage = $newCampaign['message'];
                            $storeMessage = str_replace('{{cstName}}', explode(" ", $customerList['name'])[0], $storeMessage);
                            $newmessage = $storeMessage;
                            $methods = new \App\Common\Methods();
                            if($newCampaign['embedcode']){
                                $promoCode  = $methods->getpromo();
                                $newmessage = $storeMessage." CPN- ".$promoCode;
                            }
                            $apiResonpse = ['apiResponse' => ''];
                            if($num = $methods->checkNum($customerList['contact_no'])){
                                $apiResonpse = $methods->smsgshup(
                                    $num,
                                    $newmessage,
                                    $clientInfo['brand']['client']['id'],
                                    $clientInfo['brand']['client']['senderid'],
                                    "Recurring SMS Campaigns (BIRTHDAY)",
                                    $customerList['id'],
                                    $newCampaign['store_id']
                                );
                                $externalID = explode("|", $apiResonpse['apiResponse']);
                                $externalID = trim($externalID[2]);
                                $apijsonResonpse = (json_encode($apiResonpse));
                                $data = [
                                                'customer_id' => $customerList['id'],
                                                'store_id' => $newCampaign['store_id'],
                                                'campaign_id' => $newCampaign['id'],
                                                'promo_code' => $promoCode,
                                                'api_response' => $apijsonResonpse,
                                                'api_key' => $apiResonpse['status'],
                                                'status' => 0,
                                                'cost_multiplier' => @$apiResonpse['response']['units'],
                                                'external_msgid' => $externalID
                                            ];
                                $message = $this->Messages->patchEntity($message, $data);
                                $results[] = $this->Messages->save($message);
                            }
                            unset($message);
                        }
                    }
                }
            }
        }
        exit;
    }
    /*-------------------------------------Anniversay PLIVO MESSAGE------------------------------------ */
    
    public function repeatsmsanniversary(){
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $todayDate = date('m/d/Y');
        $currentTime = date('H');
        $this->loadModel('Campaigns');
        $this->loadModel('Customers');
        $this->loadModel('Messages');
        $newCampaigns = $this->Campaigns->find()
                ->select([
                    'Campaigns.id','Campaigns.repeat','Campaigns.status','Campaigns.message', 'Campaigns.campaign_type', 'Campaigns.send_before_day',
                    'Campaigns.embedcode','Campaigns.store_id','Campaigns.send_at'
                ])
                ->hydrate(false)
                ->where([
                    'Campaigns.repeat' => 1,
                    'Campaigns.status' => 1,
                    'Campaigns.campaign_type' => "sms",
                    'Campaigns.repeat_type' => "anniversary"
                ])
                ->toArray();
        $this->loadModel('Stores');        
        foreach($newCampaigns as $newCampaign){
            if($currentTime == explode(":", @$newCampaign['send_at'])[0]){
                $addDays = $newCampaign['send_before_day'];
                $Date =  date("m/d");
                $month = date('m', strtotime($Date. "  + $addDays days"));
                $date = date('d', strtotime($Date. "  + $addDays days"));
                $customerLists = $this->Customers->find()
                        ->hydrate(FALSE)
                        ->where([
                            'store_id' => $newCampaign['store_id'],
                            'contact_no !=' => '',
                            "DAY(str_to_date(`doa`,'%m/%d/%Y'))" => $date, "MONTH(str_to_date(`doa`,'%m/%d/%Y'))" => $month
                        ])
                        ->select(['id', 'name', 'contact_no'])
                        ->toArray();
                foreach($customerLists as $customerList){
                    $clientInfo = $this->Stores->find()
                                    ->hydrate(false)
                                    ->contain([
                                        "Brands" => function($q){return $q->select(['id','client_id']);},
                                        'Brands.Clients' => function($q){
                                            return $q->select(['id','sms_quantity','senderid','plivo_auth_id','plivo_auth_token']);
                                        }])
                                    ->select(['Stores.id','Stores.brand_id'])
                                    ->where(['Stores.id' => $newCampaign['store_id'],'Stores.status' => 1])->first();
                    $checkMobileNo = preg_match("^[789]\d{9}$^", $customerList['contact_no']);
                    if($checkMobileNo){
                        if($clientInfo['brand']['client']['sms_quantity']>0){
                            $message = $this->Messages->newEntity();
                            $promoCode = "";
                            $storeMessage = $newCampaign['message'];
                            $storeMessage = str_replace('{{cstName}}', explode(" ", $customerList['name'])[0], $storeMessage);
                            $newmessage = $storeMessage;
                            $methods = new \App\Common\Methods();
                            if($newCampaign['embedcode']){
                                $promoCode  = $methods->getpromo();
                                $newmessage = $storeMessage." CPN- ".$promoCode;
                            }
                            $apiResonpse = ['apiResponse'=>''];
                            if($num = $methods->checkNum($customerList['contact_no'])){
                                $apiResonpse = $methods->smsgshup(
                                    $num,
                                    $newmessage,
                                    $clientInfo['brand']['client']['id'],
                                    $clientInfo['brand']['client']['senderid'],
                                    "Recurring SMS Campaigns (ANNIVERSARY)",
                                    $customerList['id'],
                                    $newCampaign['store_id']
                                );

                                $externalID = explode("|", $apiResonpse['apiResponse']);
                                $externalID = trim($externalID[2]);
                                $apijsonResonpse = (json_encode($apiResonpse));
                                $data = [
                                                'customer_id' => $customerList['id'],
                                                'store_id' => $newCampaign['store_id'],
                                                'campaign_id' => $newCampaign['id'],
                                                'promo_code' => $promoCode,
                                                'api_response' => $apijsonResonpse,
                                                'api_key' => $apiResonpse['status'],
                                                'status' => 0,
                                                'cost_multiplier' => @$apiResonpse['response']['units'],
                                                'external_msgid' => $externalID
                                            ];
                                $message = $this->Messages->patchEntity($message, $data);
                                $results[] = $this->Messages->save($message);
                            }
                            unset($message);
                        }
                    }
                }
            }
        }
        exit;
    }
    
    /*-------------------------------------ONE TIME SEND EMAIL ------------------------------------ */
    
    public function nonrepeatemail(){
        $gmc= new \GearmanClient();
        $gmc->addServer();
        $gmc->setCompleteCallback([$this,"reverse_complete"]);
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $todayDate = date('Y/m/d H:00');
        $this->loadModel('Campaigns');
        $this->loadModel('Messages');
        $newCampaigns = $this->Campaigns->find()
                ->Contain([
                    'Messages' => function($q){return $q->select([
                                'Messages.promo_code','Messages.customer_id','Messages.status','Messages.campaign_id','Messages.store_id','Messages.id'
                            ])
                            ->where(['Messages.status' => 1]);},
                    'Messages.Customers' => function($q){return $q->where(['Customers.email !=' => ''])->select(['Customers.name','Customers.email']);},
                ])
                ->select([
                    'Campaigns.id','Campaigns.repeat','Campaigns.status','Campaigns.message', 'Campaigns.campaign_type', 'Campaigns.send_date',
                    'Campaigns.subject'
                ])
                ->hydrate(false)
                ->where([
                    'Campaigns.repeat' => 0,
                    'Campaigns.status' => 1,
                    'Campaigns.campaign_type' => "email",
                    'Campaigns.send_date' => $todayDate
                ])
                ->toArray();

        $this->loadModel('Stores');
        foreach($newCampaigns as $newCampaign){
            $check = $this->Campaigns->find()->where(['status' => 1,'id' => $newCampaign['id']]);
            if($check){
            $this->Campaigns->updateAll(['status' => 0],['id' => $newCampaign['id']]);
                foreach($newCampaign['messages'] as $message){
                    $dt = [
                        "message"=>$newCampaign['message'],
                        'store_id'=>$message['store_id'],
                        'email' => $message['customer']['email'],
                        'promo_code' => $message['promo_code'],
                        'cst_name' => $message['customer']['name'],
                        'message_id' => $message['id'],
                        'subject' => $newCampaign['subject'],
                        'campaign_id' => $newCampaign['id']
                    ];
                    $task= $gmc->addTaskBackground("nonrepeatemail", serialize($dt),null);
                    continue;
                }
            }
            $this->Campaigns->updateAll(['status' => 0],['id' => $newCampaign['id']]);
        }
        $gmc->runTasks();
        exit;
    }
    
    /*-------------------------------------BirthDay EMAIL------------------------------------ */
    
    public function repeatemailbday(){
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $todayDate = date('m/d/Y');
        $currentTime = date('H');
        $this->loadModel('Campaigns');
        $this->loadModel('Customers');
        $this->loadModel('Messages');
        $newCampaigns = $this->Campaigns->find()
                ->select([
                    'Campaigns.id','Campaigns.repeat','Campaigns.status','Campaigns.message', 'Campaigns.campaign_type', 'Campaigns.send_before_day',
                    'Campaigns.embedcode','Campaigns.store_id','Campaigns.send_at','Campaigns.subject'
                ])
                ->hydrate(false)
                ->where([
                    'Campaigns.repeat' => 1,
                    'Campaigns.status' => 1,
                    'Campaigns.campaign_type' => "email",
                    'Campaigns.repeat_type' => "birthday"
                ])
                ->toArray();
        $this->loadModel('Stores');        
        foreach($newCampaigns as $newCampaign){
            if($currentTime == explode(":", @$newCampaign['send_at'])[0]){
                $addDays = $newCampaign['send_before_day'];
                $Date =  date("m/d");
                $month = date('m', strtotime($Date. "  + $addDays days"));
                $date = date('d', strtotime($Date. "  + $addDays days"));
                $customerLists = $this->Customers->find()
                        ->hydrate(FALSE)
                        ->where([
                            'store_id' => $newCampaign['store_id'],
                            'email !=' => '',
                            "DAY(str_to_date(`dob`,'%m/%d/%Y'))" => $date, "MONTH(str_to_date(`dob`,'%m/%d/%Y'))" => $month
                        ])
                        ->select(['id', 'name', 'email'])
                        ->toArray();
                foreach($customerLists as $customerList){
                    $clientInfo = $this->Stores->find()
                                    ->hydrate(false)
                                    ->contain([
                                        "Brands" => function($q){return $q->select(['id','client_id']);},
                                        'Brands.Clients' => function($q){return $q->select(['id','email_left']);}])
                                    ->select(['Stores.id','Stores.brand_id'])
                                    ->where(['Stores.id' => $newCampaign['store_id'],'Stores.status' => 1])->first();
                                       
                    $verifyEmail = filter_var($customerList['email'], FILTER_VALIDATE_EMAIL);
                        if($verifyEmail){
                            if($clientInfo['brand']['client']['email_left']>0){
                                $message = $this->Messages->newEntity();
                                $promoCode = "";
                                $storeMessage = $newCampaign['message'];
                                $storeMessage = str_replace('{{cstName}}', explode(" ", $customerList['name'])[0], $storeMessage);
                                $newmessage = $storeMessage;
                                $methods = new \App\Common\Methods();
                                if($newCampaign['embedcode']){
                                    $promoCode  = $methods->getpromo();
                                    $newmessage = $storeMessage." CPN- ".$promoCode;
                                }
                                $apiResonpse = $methods->sendemail(
                                    $customerList['email'],
                                    $newmessage,
                                    $clientInfo['brand']['client']['id'],
                                    $newCampaign['subject'],
                                    $newCampaign['store_id']
                                );
                                $apijsonResonpse = (json_encode($apiResonpse));
                                $data = [
                                        'customer_id' => $customerList['id'],
                                        'store_id' => $newCampaign['store_id'],
                                        'campaign_id' => $newCampaign['id'],
                                        'promo_code' => $promoCode,
                                        'api_response' => $apijsonResonpse,
                                        'api_key' => $apiResonpse[0]['status'],
                                        'status' => 0
                                    ];
                                $message = $this->Messages->patchEntity($message, $data);
                                $results[] = $this->Messages->save($message);
                                unset($message);
                            }
                    }
                }
            }
        }
        exit;
    }
    
    /*-------------------------------------Anniversay Email------------------------------------ */
    
    public function repeatemailanniversary(){
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $todayDate = date('m/d/Y');
        $currentTime = date('H');
        $this->loadModel('Campaigns');
        $this->loadModel('Customers');
        $this->loadModel('Messages');
        $newCampaigns = $this->Campaigns->find()
                ->select([
                    'Campaigns.id','Campaigns.repeat','Campaigns.status','Campaigns.message', 'Campaigns.campaign_type', 'Campaigns.send_before_day',
                    'Campaigns.embedcode','Campaigns.store_id','Campaigns.send_at','Campaigns.subject'
                ])
                ->hydrate(false)
                ->where([
                    'Campaigns.repeat' => 1,
                    'Campaigns.status' => 1,
                    'Campaigns.campaign_type' => "email",
                    'Campaigns.repeat_type' => "anniversary"
                ])
                ->toArray();
        $this->loadModel('Stores');        
        foreach($newCampaigns as $newCampaign){
            if($currentTime == explode(":", @$newCampaign['send_at'])[0]){
                $addDays = $newCampaign['send_before_day'];
                $Date =  date("m/d");
                $month = date('m', strtotime($Date. "  + $addDays days"));
                $date = date('d', strtotime($Date. "  + $addDays days"));
                $customerLists = $this->Customers->find()
                        ->hydrate(FALSE)
                        ->where([
                            'store_id' => $newCampaign['store_id'],
                            'email !=' => '',
                            "DAY(str_to_date(`doa`,'%m/%d/%Y'))" => $date, "MONTH(str_to_date(`doa`,'%m/%d/%Y'))" => $month
                        ])
                        ->select(['id', 'name', 'email'])
                        ->toArray();
                foreach($customerLists as $customerList){
                    $clientInfo = $this->Stores->find()
                        ->hydrate(false)
                        ->contain([
                            "Brands" => function($q){return $q->select(['id','client_id']);},
                            'Brands.Clients' => function($q){return $q->select(['id','email_left']);}])
                        ->select(['Stores.id','Stores.brand_id'])
                        ->where(['Stores.id' => $newCampaign['store_id'],'Stores.status' => 1])->first();
                    $verifyEmail = filter_var($customerList['email'], FILTER_VALIDATE_EMAIL);
                    if($verifyEmail){
                        if($clientInfo['brand']['client']['email_left']>0){
                            $message = $this->Messages->newEntity();
                            $promoCode = "";
                            $storeMessage = $newCampaign['message'];
                            $storeMessage = str_replace('{{cstName}}', explode(" ", $customerList['name'])[0], $storeMessage);
                            $newmessage = $storeMessage;
                            $methods = new \App\Common\Methods();
                            if($newCampaign['embedcode']){
                                $promoCode  = $methods->getpromo();
                                $newmessage = $storeMessage." CPN- ".$promoCode;
                            }
                            $apiResonpse = $methods->sendemail(
                                $customerList['email'],
                                $newmessage,
                                $clientInfo['brand']['client']['id'],
                                $newCampaign['subject'],
                                $newCampaign['store_id']
                            );
                            $apijsonResonpse = (json_encode($apiResonpse));
                            $data = [
                                    'customer_id' => $customerList['id'],
                                    'store_id' => $newCampaign['store_id'],
                                    'campaign_id' => $newCampaign['id'],
                                    'promo_code' => $promoCode,
                                    'api_response' => $apijsonResonpse,
                                    'api_key' => $apiResonpse[0]['status'],
                                    'status' => 0
                                ];
                            $message = $this->Messages->patchEntity($message, $data);
                            $results[] = $this->Messages->save($message);
                            unset($message);
                        }
                    }
                }
            }
        }
        exit;
    }
    
    /*-------------------------------------Album Expire Time------------------------------------ */
    
    public function albumExpire(){
        $today = date('m/d/Y');
        $this->loadModel('Albumshares');
        $albumshares = $this->Albumshares->find()->select(['id'])->where(['expired' => $today,'status' => 1])->hydrate(FALSE)->toArray();
        if($albumshares){
            foreach($albumshares as $albumshare){
                $this->Albumshares->updateAll(['status' => 0, 'soft_deleted' => 1],['id' => $albumshare['id']]);
            }
        }
        exit;
    }
     
    /*----------------------------------------Update Mandrill Email Response---------------------------*/
    
    public function mandrillresponse(){
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $this->loadModel('Messages');
        \Cake\Core\Configure::write('debug',TRUE);
        $methods = new \App\Common\Methods();
        $messagesList = $this->Messages->find()->hydrate(false)->select(['id','api_key','api_response','open'])->where(['api_key'=>'sent'])->toArray();
        foreach($messagesList as $messageList){
            $apiResponse = json_decode($messageList['api_response']);
            $result = $methods->mandrillresponse($apiResponse[0]->_id);
            $this->Messages->updateAll(['open' => $result['opens']], ['id' => $messageList['id']]);
        }
        exit;
    }

    /*----------------------------------------------Update Plivo Response---------------------------*/

    public function plivoresponse(){
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $methods = new \App\Common\Methods();
//        \Cake\Core\Configure::write('debug',true);
        $this->loadModel('Messages');
        $messagesList = $this->Messages->find()->hydrate(FALSE)->select(['id','api_key','api_response','open'])
                ->where(['open' => '','api_key'=>'202'])->toArray();
        foreach($messagesList as $messageList){
            $apiResponse = json_decode($messageList['api_response']);
            $result = $methods->plivoresponse($apiResponse->response->message_uuid[0]);
            $res = $this->Messages->updateAll(['open' => $result['response']['message_state']], ['id' => $messageList['id']]);
        }
        $messagesList1 = $this->Messages->find()->hydrate(FALSE)->select(['id','api_key','api_response','open'])
                ->where(['open' => '','api_key'=>'200'])->toArray();
        foreach($messagesList1 as $messageList1){
            $apiResponse = json_decode($messageList1['api_response']);
            $result = $methods->plivoresponse($apiResponse->response->message_uuid);
            $res1 = $this->Messages->updateAll(['open' => $result['response']['message_state']], ['id' => $messageList1['id']]);
        }
        exit;
    }
    
    /*---------------------------------Mobile Push Notifications --------------------------------*/
    
    public function mobilepush(){
        \Cake\Core\Configure::write('debug',TRUE);
        $this->loadModel('Pushmessages');
        $this->loadModel('Notifications');
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $todayDate = date('Y/m/d H:00');
        $setTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $pushmessages = $this->Pushmessages->find()
                ->select(['store_slug','id','message','image','store_id','title'])
                ->where([
                    'Pushmessages.at_time' => $todayDate,
                    'Pushmessages.status' => 1
                ])
                ->toArray();
        foreach($pushmessages as $pushmessage){
            $androidApiKey = file_get_contents(WWW_ROOT.'gsmKey'.DS.md5($pushmessage->store_id).".txt");
            $strSet = $setTbl->find()->where([
                'StoreSettings.store_id' =>  $pushmessage->store_id,
                'StoreSettings.meta_key' => 'ios-pem'
            ])->first();

            $this->Pushmessages->updateAll(['status' => 0], ['id' => $pushmessage->id]);
            $notifyCustomers = $this->Notifications->find()
                    ->hydrate(false)
                    ->select(['Notifications.device_token','Notifications.type'])
                    ->where(['Notifications.store_slug' => $pushmessage->store_slug,'Notifications.status' => 1])
                    ->toArray();
            $methods =  new \App\Common\Methods();
            foreach($notifyCustomers as $notifyCustomer){
                if($pushmessage->image){$image = $pushmessage->android_api_img;}else{$image = 0;}
                if($notifyCustomer['type'] == "ios"){
                    if(!$strSet){
                        continue;
                    }
                    $iosPem = json_decode($strSet->meta_value);
                    $response[] = $methods->iospush($notifyCustomer['device_token'],
                            $pushmessage->title,
                            $pushmessage->message,
                            $image,
                            md5($pushmessage->store_id).'.pem',//$iosPem->pemfile,
                            $iosPem->pemkey
                        );
                }elseif($notifyCustomer['type'] == "android"){
                    $response[] = $methods->gcmNotification(
                            $notifyCustomer['device_token'],
                            $pushmessage->title,
                            $pushmessage->message,
                            $image,
                            $androidApiKey
                    );
                }
            }
        }
        exit;
    }

    public function androidApiKey($stId = NULL, $andApiKey = NULL){
        \Cake\Core\Configure::write('debug',TRUE);
        $d = $this->request->data;
        $result = [
                'error' => 1,
                'msg' => 'Something went wrong please try again'
            ];
        /*$apiKeys = [
            [
                'store_id' => 6,
                'apiKey' => 'AIzaSyB-pNelyJFwqW8jWGo6K1x4uHmXQcYjRTs'
            ],
            [
                'store_id' => 16,
                'apiKey' => 'AIzaSyDvA1LWiiLGa5J9F5ly29xZJiUi-YKNwNM'
            ],
            [
                'store_id' => 46,
                'apiKey' => 'AIzaSyAlNP25ZqmGpBYz0uisTj5k0c_oeLmwRyg'
            ],
            [
                'store_id' => 5,
                'apiKey' => 'AIzaSyBfog8zlbCrD5NWjrB7tiSfSOEQU_hQ-Vo'
            ],
            [
                'store_id' => 26,
                'apiKey' => 'AIzaSyCKvkKAem3hE8iclVEUDFpqkZlY0jEW9lw'
            ],
            [
                'store_id' => 31,
                'apiKey' => 'AIzaSyBZAPgMhufhx4EHdGZl2Fx7DUyBlz6ToP4'
            ],
            [
                'store_id' => 43,
                'apiKey' => 'AIzaSyC5IO-tCahEr2sU3foX2pqJ9dQx0Pja-_Q'
            ],
            [
                'store_id' => 28,
                'apiKey' => 'AIzaSyBo491v9iZBV3GxOccFfJD6bh-yBH_V5os'
            ],
            
        ];
        if($apiKeys){
            foreach($apiKeys as $apiKey){
                $myfile = fopen(WWW_ROOT.'gsmKey'.DS.md5($apiKey['store_id']).".txt", "w");
                $androidApiKey = file_put_contents(WWW_ROOT.'gsmKey'.DS.md5($apiKey['store_id']).".txt",print_r($apiKey['apiKey'],true));
            }
        }
         * 
         */
        $myfile = fopen(WWW_ROOT.'gsmKey'.DS.md5($stId).".txt", "w");
        $androidApiKey = file_put_contents(WWW_ROOT.'gsmKey'.DS.md5($stId).".txt",print_r($andApiKey,true));
        if($androidApiKey){
            $result = [
                'error' => 0,
                'msg' => 'Android key has been updated successfully'
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize',['result']);
    }
    

    
    public function ios1($tToken = NULL){
        \Cake\Core\Configure::write('debug',true);
        $methods= new \App\Common\Methods();
        $tbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $iosPems = $tbl->find()->where([
            'StoreSettings.meta_key' => 'ios-pem'
        ])->all();
        $res = [];
        foreach ($iosPems as $ipem){
            $pm = json_decode($ipem->meta_value);
            $res[] = [
               "store_id" => $ipem->store_id,
               "cert" => $ipem->meta_value,
               "APNSResult" => $methods->iospush($tToken,'Kamal','This is for ios testing',0,$pm->pemfile,$pm->pemkey)
            ];
        }
        echo '<pre>';
        print_r($res);
        exit;
    }
    public function customer(){
        \Cake\Core\Configure::write('debug',true);
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $this->loadModel('Customers');
        $customers = $this->Customers->find()->hydrate(false)->toArray();
        foreach($customers as $customer){
            $timestamp = strtotime($customer['created']);
            $result[] = $this->Customers->updateAll(['created' => $timestamp],['id' => $customer['id']]);
        }
        $this->set(compact($result));
        $this->set('_serialize', ['result']);
    }
    /**
     * Customer Contact Number Is  Check
     */
    
    public function contactIsCheck(){
        \Cake\Core\Configure::write('debug',TRUE);
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $this->loadModel('Customers');
            $customersList= $this->Customers->Messages->find()
                    ->select(['id','customer_id'])
                    ->hydrate(false)
                    ->where([
                        'customer_id <>' => '0',
                        'OR' => [
                            'cause NOT IN' => ['SUCCESS','success'],
                            'open' => 'undelivered'
                        ]
                    ])
                    ->group(['customer_id'])
                    ->order(['id desc'])
                    ->toArray();
            foreach($customersList as $customerList){
                $str = $this->Customers->Messages->find()
                        ->where([
                            'customer_id' => $customerList['customer_id'],
                            'OR' => ['cause IN' => ['SUCCESS', 'success'],
                                'open IN' => ['sent','delivered']]
                        ])
                        ->first();
                if(!$str){
                    $this->Customers->updateAll(['is_sent' => 0], ['id' => $customerList['customer_id']]);
                }
            }
        exit;
    }
    /**
     * This one is for daily reports and is used in crontab
     * 
     */
    public function dailyReport(){
        $date = date('Y-m-d');
        $date = strtotime($date);
        $this->loadModel('Stores');
        $time =  date("H:00");
        
        $checkSmsTime = $this->Stores->StoreSettings->find('list',['keyField' =>'id','valueField' => 'store_id'])->where([
            'meta_value' => $time,
            'meta_key' => 'daily-sms-report-on-what-time'
        ]);
        if($checkSmsTime->toArray()){
            $stores = $this->Stores->find()->where(['Stores.id IN' => $checkSmsTime->toArray(),'Stores.status' => 1])
                    ->contain([
                            "Brands" => function($q){return $q->select(['id','client_id']);},
                            'Brands.Clients' => function($q){return $q->select(['id','name','sms_quantity','senderid']);}])
                        ->select(['Stores.id','Stores.brand_id'])
                    ->toArray();
            foreach($stores as $store){
                $storeSettings = $this->Stores->StoreSettings->find()->where(['meta_key' => "daily-sms-report",'store_id' => $store->id])->first();
                if($storeSettings->meta_value == 1){
                    $contactNo = $this->Stores->StoreSettings->find()->where(['meta_key' => "daily-sms-report-on-mobile",'store_id' => $store->id])->first();
                    $checkMobileNo = preg_match("^[789]\d{9}$^", $contactNo->meta_value);
                    if($checkMobileNo){
                        $customer['today'] = $this->Stores->Customers->find()->where([
                            'created >=' => $date,
                            'store_id' => $store->id
                        ])->count();
                        $customer['total'] = $this->Stores->Customers->find()->where([
                            'store_id' => $store->id
                        ])->count();
                        if($customer['today']){
                            $todayCustmer = $customer['today'];
                        }else{
                            $todayCustmer = "no";
                        }
                        $newmessage = "Hi! ".$todayCustmer." people registered at your store today. Total ". $customer['total']." registered customers till ".date('d-m-Y');
                        $methods = new \App\Common\Methods();
                        if($num = $methods->checkNum($contactNo->meta_value)){
                            $apiResonpse = $methods->smsgshup(
                                $num,
                                $newmessage,
                                $store['brand']['client']['id'],
                                $store['brand']['client']['senderid'],
                                "Daily Status Reports",
                                0,
                                $store->id
                            );
                        }
                    }
                }
            }
        }
        exit;
    }
    
    /**
     * Client Sms For Low Balance (SMS/EMAIL)
     */
    
    
    public function lowBalanceAlert(){
        \Cake\Core\Configure::write('debug',TRUE);
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        
        $clientTbl = \Cake\ORM\TableRegistry::get('Clients');
        $methods = new \App\Common\Methods();
        $clients = $clientTbl->find();
        foreach($clients as $client){
            if($client->sms_quantity <= 100){
                if($client->low_balance2_sms_pause == 0){
                    $alertMsg = "Hi! Your current SMS balance is low (" . $client->sms_quantity . " left). Contact us to recharge today - Team Zakoopi";
                    $methods->smsgshup($client->contact_no, $alertMsg);
                    $client->low_balance2_sms_pause = 1;
                    $clientTbl->save($client);
                }
            }
            if($client->email_left <= 100){
                if($client->low_balance2_email_pause == 0){
                    $alertMsg = "Hi! Your current Email balance is low (" . $client->email_left . " left). Contact us to recharge today - Team Zakoopi";
                    $methods->smsgshup($client->contact_no, $alertMsg);
                    $client->low_balance2_email_pause = 1;
                    $clientTbl->save($client);
                }
            }
        }
        exit;
    }

    /**
     * Client Notification SMS Balance 
     */
    public function tenDaysBalanceAlert(){
        \Cake\Core\Configure::write('debug',TRUE);
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        if(!in_array(date('d'), [7,17,27])){
            exit;
        }
        $clientTbl = \Cake\ORM\TableRegistry::get('Clients');
        $methods = new \App\Common\Methods();
        $clients = $clientTbl->find();
        foreach($clients as $client){
            $alertMsg = "Hi! Your current SMS and Email balance is ".$client->sms_quantity." & ".$client->email_left.", respectively. Plan your next campaign soon - Team Zakoopi";
            $methods->smsgshup($client->contact_no, $alertMsg);
        }
        exit;
    }

    
    /**
     * Send Customer Sms About his/her last visit to store
     */
    
    
    public function lastVistNotify(){
        \Cake\Core\Configure::write('debug',true);
        $campaginsTbl = \Cake\ORM\TableRegistry::get('Campaigns');
        $cstVisitsTbl = \Cake\ORM\TableRegistry::get('CustomerVisits');
        $methods = new \App\Common\Methods();
        $this->loadModel('Messages');
        $this->loadModel('Stores');
        $lastVistCampaigns = $campaginsTbl->find()->select(['id','store_id','message','send_before_day','campaign_type','repeat_type','embedcode'])
                ->where(['Campaigns.repeat_type' => 'last_vist','Campaigns.campaign_type' => 'sms','Campaigns.status'=> 1])->toArray();
        foreach($lastVistCampaigns as $lastVistCampaign){
            $date_before_45_days = date('m-d-Y',strtotime("-$lastVistCampaign->send_before_day days"));
            $customerVisits = $cstVisitsTbl->find()->contain(['Customers'])->where([
                "CustomerVisits.store_id" => $lastVistCampaign->store_id,
                "DATE_FORMAT(from_unixtime(`CustomerVisits`.`came_at`),'%m-%d-%Y') = '$date_before_45_days'"
            ])->group('customer_id');
            foreach($customerVisits as $customerVisit){
                $clientInfo = $this->Stores->find()
                                    ->contain([
                                        "Brands" => function($q){return $q->select(['id','client_id']);},
                                        'Brands.Clients' => function($q){
                                            return $q->select(['id','sms_quantity','senderid']);
                                        }])
                                    ->select(['Stores.id','Stores.brand_id'])
                                    ->where(['Stores.id' => $lastVistCampaign->store_id,'Stores.status' => 1])->first();
                                        
                $checkMobileNo = preg_match("^[789]\d{9}$^", $customerVisit->customer->contact_no);
                if($checkMobileNo){
                    if($clientInfo->brand->client->sms_quantity>0){
                        $lastVisitMessage = $lastVistCampaign->message;
                        $promoCode = "";
                        $lastVisitMessage = str_replace('{{cstName}}', explode(" ", $customerVisit->customer->name)[0], $lastVisitMessage);
                        if($lastVistCampaign['embedcode']){
                            $promoCode  = $methods->getpromo();
                            $lastVisitMessage = $lastVisitMessage." CPN- ".$promoCode;
                        }
                        $message = $this->Messages->newEntity();
                        $apiResonpse = $methods->smsgshup(
                            $customerVisit->customer->contact_no,
                            $lastVisitMessage,
                            $clientInfo->brand->client->id,
                            $clientInfo->brand->client->senderid,
                            "Auto reminder ($lastVistCampaign->send_before_day Days)",
                            $customerVisit->customer->id,
                            $clientInfo->id
                        );
                        $externalID = explode("|", $apiResonpse['apiResponse']);
                        $externalID = trim($externalID[2]); //'external_msgid' => $externalID
                        $apijsonResonpse = (json_encode($apiResonpse));
                        $data = [
                            'customer_id' => $customerVisit->customer->id,
                            'store_id' => $clientInfo->id,
                            'campaign_id' => $lastVistCampaign->id,
                            'promo_code' => $promoCode,
                            'api_response' => $apijsonResonpse,
                            'api_key' => $apiResonpse['status'],
                            'status' => 0,
                            'cost_multiplier' => @$apiResonpse['response']['units'],
                            'external_msgid' => $externalID
                        ];
                        $message = $this->Messages->patchEntity($message, $data);
                        $results[] = $this->Messages->save($message);
                    }
                }
            }
        }
        exit;
        $this->set(compact('customerVisits'));
        $this->set('_serialize',['customerVisits']);
    }
    
    /**
     * Update Gupshup Log After One Day
     */
    
    public function gupshupLog(){
        \Cake\Core\Configure::write('debug',true);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        $this->loadModel('Gshuplogs');
        $this->loadModel('Messages');
        
        $date = date("Y-m-d");
        $prev_date = date('m-d-Y', strtotime($date .' -1 day'));
        
//        $res = $this->Gshuplogs->find()->select([
//            'id', 'external_id', 'status', "created", "extras"
//        ])->where([
//            "FROM_UNIXTIME(`created`,'%m-%d-%Y')" => $prev_date,
//            'OR' => [
//                ['BINARY(status) in ' => ['success','DEFERRED']],
//                ['status IS NULL']
//            ]
//        ]);
//        
//        debug($res->toArray());exit;
        
        
        $q = "SELECT `id`, `external_id`, `status`, DATE_FORMAT(from_unixtime(`created`),'%m-%d-%Y') as crt, `extras` 
                FROM `gshuplogs` WHERE DATE_FORMAT(from_unixtime(`created`),'%m-%d-%Y') = '$prev_date' and
                (BINARY `status` in ('success','DEFERRED') or `status` IS NULL)";
        $conn = \Cake\Datasource\ConnectionManager::get('default');
        $res = $conn->execute($q)->fetchAll('assoc');
        foreach($res as $r){
            $r1 = file_get_contents('http://enterprise.smsgupshup.com/custdlr/api/rest.php?accId=2000153962&password=zakoopi&causeId=' . $r['external_id']);
            $r1 = json_decode($r1);
            if(@$r1->status){
                $status = $r1->status;
                $cause =NULL;
            }elseif($r1[0]->status){
                $status = $r1[0]->status;
                $cause = $r1[0]->cause;
                $time = time();
                $qry = "update `gshuplogs` set `status` ='".$status."', `modified` ='".$time."' where id=".$r['id'];
                $conn->execute($qry);
                $msgEnt = $this->Messages->find()->where([
                    "Messages.external_msgid" => $r['external_id']
                ])->first();
                //file_put_contents("rq.txt", print_r($msgEnt,true));
				
                if($msgEnt){
                    $this->Messages->updateAll(['cause' => $cause], ['external_msgid' => $r['external_id']]);
                }
            }
        }
        exit;
    }
    
    /*
     * Requeue Skip Messages of campaigns
     */
    
    public function skipMessages(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        $this->loadModel('Campaigns');
        $this->loadModel('Stores');
        $this->loadModel('Messages');
        $todayDate = date("Y/m/d H:00", strtotime('-5 hours'));
        $todayDate1 = date("Y/m/d H:00", strtotime('-6 hours'));
        $methods = new \App\Common\Methods();
        $campaignsMessages = $this->Campaigns->find()->where([
            'Campaigns.status' => 0,
            'Campaigns.send_date <=' => $todayDate,
            'Campaigns.send_date >=' => $todayDate1,
            'Campaigns.send_date <>' => ''
        ])
        ->contain([
            'Messages' => function($q){
                return $q->where(['Messages.status' => 1])->select([
                    'Messages.promo_code','Messages.customer_id','Messages.status','Messages.campaign_id','Messages.store_id','Messages.id'
                ])->contain([
                    'Customers' => function ($q){
                        return $q->where(['Customers.contact_no !=' => '','Customers.opt_in' => 1])->select(['Customers.name','Customers.contact_no']);
                    }
                ]);
            }
        ])
        ->orderDesc('id')->toArray();
        foreach($campaignsMessages as $campaignsMessage){
            foreach($campaignsMessage['messages'] as $message){
                $clientInfo = $this->Stores->find()
                    ->hydrate(false)
                    ->contain([
                        "Brands" => function($q){return $q->select(['id','client_id']);},
                        'Brands.Clients' => function($q){return $q->select(['id','sms_quantity','senderid','plivo_auth_id','plivo_auth_token']);}])
                    ->select(['Stores.id','Stores.brand_id'])
                    ->where(['Stores.id' => $message['store_id'],'Stores.status' => 1])->first();
                $checkMobileNo = preg_match("^[789]\d{9}$^", $message['customer']['contact_no']);
                if($checkMobileNo){
                    if($clientInfo['brand']['client']['sms_quantity']>0){
                        $storeMessage = $campaignsMessage['message'];
                        $storeMessage = str_replace('{{cstName}}', explode(" ", $message['customer']['name'])[0], $storeMessage);
                        $newmessage = $storeMessage;
                        if($message['promo_code']){
                            $newmessage = $storeMessage." CPN- ".$message['promo_code'];
                        }
                        if($clientInfo['brand']['client']['senderid']){
                            $apiResonpse = $methods->plivo(
                                $message['customer']['contact_no'],
                                $newmessage,
                                $clientInfo['brand']['client']['id'],
                                $clientInfo['brand']['client']['senderid'],
                                $clientInfo['brand']['client']['plivo_auth_id'],
                                $clientInfo['brand']['client']['plivo_auth_token']
                            );
                        }else{
                            $apiResonpse = $methods->smsgshupCampaign(
                                $message['customer']['contact_no'],
                                $newmessage,
                                $clientInfo['brand']['client']['id'],
                                $clientInfo['brand']['client']['senderid']
                            );
                        }
                        $externalID = explode("|", $apiResonpse['apiResponse']);
                        $externalID = trim($externalID[2]); //'external_msgid' => $externalID
                        $apijsonResonpse = (json_encode($apiResonpse));
                        $this->Messages->updateAll(
                                [
                                    'status' => 0,
                                    'api_key' => $apiResonpse['status'],
                                    'api_response' => $apijsonResonpse,
                                    'cost_multiplier' => @$apiResonpse['response']['units'],
                                    'external_msgid' => $externalID
                                ],
                                [
                                    'id' => $message['id']
                                ]
                        );
                    }
                }
            }
        }
        exit;
    }
    
    /*
     * Update Gupshup Log In Message Table 
     */
    
    public function messageGupshupRes(){
        \Cake\Core\Configure::write('debug',true);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        $this->loadModel('Messages');
        $date = date("Y-m-d");
        $prev_date = date('m-d-Y', strtotime($date .' -1 day'));
        $message = $this->Messages->find()->where([
            'external_msgid <>' => '','cause IS' => NULL,"FROM_UNIXTIME(`created`,'%m-%d-%Y')" => $prev_date,
            ]);
        $messages = $message->toArray();
        foreach ($messages as $m){
            $r1 = file_get_contents('http://enterprise.smsgupshup.com/custdlr/api/rest.php?accId=2000153962&password=zakoopi&causeId=' . $m->external_msgid);
            $r1 = json_decode($r1);
            if(@$r1->status){
                $status = $r1->status;
                $cause =NULL;
            }elseif($r1[0]->status){
                $status = $r1[0]->status;
                $cause = $r1[0]->cause;
		$res = $this->Messages->updateAll(['cause' => $cause], ['id' => $m->id]);
            }
        }exit;
    }
    
    public function smsLeadger(){
        \Cake\Core\Configure::write('debug',FALSE);
	ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        $this->loadModel('Messages');
        $yesterday = strtotime("yesterday");
        $messages = $this->Messages->find()->where(['Messages.created >' => $yesterday,'Messages.status' => 0,'Messages.external_msgid <>' => ""])
                ->contain([
                    'Customers' => function($q){
                        return $q->select(['contact_no']);
                    },
                    "Stores" => function($q){return $q->select(['id'])->contain(["Brands" => function($q) {
                                return $q->select(['id', 'client_id']);
                            }]);
                    }])->orderAsc('Messages.id')
                ->toArray();
        $smslTbl = \Cake\ORM\TableRegistry::get('Smsledger');
        foreach($messages as $message){
            $smslTbl->addDebit(
                $message->cost_multiplier,
                $message->store->brand->client_id,
                "SMS Campaigns",
                $message->customer_id,
                $message->store_id,
                $message->id."|".$message->external_msgid,
                $message->customer->contact_no
            );
        }exit;
    }
    
    public function te(){
        $rows =8;
        for($i=$rows;$i>=1;--$i)
     {
         for($j=1;$j<=$i;++$j)
         {
            echo $j;
         }
     echo "<br />";
     }
        exit;
        $num =23;

        for( $j = 2; $j <= $num; $j++ )
        {
            for( $k = 2; $k < $j; $k++ )
            {
            if( $j % $k == 0 )
            {
                break;
            }

            }
        if( $k == $j )
            echo "Prime Number : ", $j, "<br>";
        }
        
        exit;
        $count = 0 ;
        $number = 2 ;
        while ($count < 3 )
        {
            $div_count=0;
            for ( $i=1;$i<=$number;$i++)
            {
                $a = $number%$i;
                if (($a)==0)
                {
                    $div_count++;
                }
            }
            if ($div_count<3)
            {
                echo $number." , ";
                $count=$count+1;
            }
            $number=$number+1;
        }exit;
        $rev=0;
         $num=371;
           
          while($num>=1)
                {
                  $re=$num%10;debug($re);
                  $rev=$rev*10+$re;debug($rev);
                  $num=$num/10;debug($num);
                 }
                 echo $rev;
                 exit;
        $n=371;
        while($n>0)
        {
            echo $b=$n%10;echo "-";
            echo $c=$b*$b*$b;echo "-";
            echo $n=$n/10;echo "-";
            echo $d=$c+$n;echo "<br/>";
        }
        if($d==$n)
            echo $n."is an armstrong number";
        else
            echo $n."is not an armstrong number";
        exit;
        
        
        $a = 23;
        $b = &$a;
        $b = 42;
        debug($a);
        debug($b);
        $no =1;
        HR:
        if($no<=100){
            echo $no++."<br/>";
            goto HR;
        }
        exit;
    }
}