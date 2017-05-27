<?php

namespace App\Common;

use Mandrill\Mandrill;
use Cake\Mailer\Email;


class Methods {

    public function getpromo($length = 6) {
        return strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length));
    }
    
    public function checkNum($num = null){
        if(!is_numeric($num)){
            return false;
        }
        if(mb_strlen($num) == 11 && $num[0] == 0){
            $num = ltrim($num, "0");
        }elseif(mb_strlen($num) != 10){
            return false;
        }
        if(preg_match("^[789]\d{9}$^", $num)){
            return $num;
        }
        return false;
    }

    /**
     * Method to send SMS via Plivo
     * @param type $contactno
     * @param type $message
     * @param type $clientId
     * @param string $senderid
     * @param type $auth_id
     * @param type $auth_token
     * @return type
     */
    public function plivo($contactno = NULL, $message = NULL, $clientId = NULL, $senderid = "ZAKOPI", $auth_id = "MAMZVKMDGXYWVLZME4YT", $auth_token = "ZjcxNjU1MTBhYjM5YzJiM2RmOWIxNDZiZTkzOTBk") {
        $multiplier = 1;
        if($senderid == ""){
            $senderid = "ZAKOPI";
        }
        $response["apiResponse"] = $this->sendViaGupShup($contactno,$message,$senderid);
        $response["costCalc"] = $this->detect($message);
        $response['status'] = 400;
        
        $gshupTbl = \Cake\ORM\TableRegistry::get('Gshuplogs');
        
        if (preg_match_all("/success/im", $response["apiResponse"]) > 0 && $clientId) {
            
            /** GshupLogs Generator **/
            $extId1 = explode("|", $response["apiResponse"]);
            $extId = trim($extId1[2]);
            $extras = "";
            if($clientId){
                $extras = "Number:".$contactno;
                $extras .= "|ClientID:".@$clientId;
                $extras .= "|Msg:".$message;
                
            }
            $gshupData = [
                "external_id" => $extId,
                "status" => trim($extId1[0]),
                "created"   => time(),
                "extras" => $extras
            ];
            $gEnt = $gshupTbl->newEntity($gshupData);
            $gshupTbl->save($gEnt);
            /** GshupLogs Generator end **/
            
            
            $response['status'] = 200;
            $response['response']['units'] = $response["costCalc"]["smsCount"];
            $multiplier = $response["costCalc"]["smsCount"];
            $clientsTbl = \Cake\ORM\TableRegistry::get('Clients');
            $res = $clientsTbl->updateAll([
                'sms_sent = sms_sent + ' . $multiplier, "sms_quantity = sms_quantity - $multiplier"
                    ], ['id' => $clientId]);
        }else{
            /** GshupLogs Generator **/
            $extId1 = explode("|", $response["apiResponse"]);
            $extId = trim(@$extId1[2]);
            $extras = "";
            
            $gshupData = [
                "external_id" => $extId,
                "status" => trim($extId1[0]),
                "created"   => time(),
                "extras" => $response["apiResponse"]
            ];
            $gEnt = $gshupTbl->newEntity($gshupData);
            $gshupTbl->save($gEnt);
            /** GshupLogs Generator end **/
        }
        return $response;
        
        // Block Plivo
//        $multiplier = 1;
//        require_once(ROOT . DS . 'webroot' . DS . 'plivo.php');
//        $p = new \RestAPI($auth_id, $auth_token);
//        $params = array(
//            'src' => $senderid, /* Sender's phone number with country code */
//            'dst' => "+91" . $contactno, /* receiver's phone number with country code */
//            'text' => $message
//        );
//        $response = $p->send_message($params);
//        if ($response['status'] == 202) {
//            $params = array('record_id' => $response['response']['message_uuid'][0]);
//            $response = $p->get_message($params);
//            $multiplier = @$response['response']['units'];
//        }
//        if ($response && $clientId) {
//            $clientsTbl = \Cake\ORM\TableRegistry::get('Clients');
//            $res = $clientsTbl->updateAll([
//                'sms_sent = sms_sent + ' . $multiplier, "sms_quantity = sms_quantity - $multiplier"
//                    ], ['id' => $clientId]);
//        }
        return $response;        
    }
    
    
    
    /**
     * Method to send SMS via Gupshup
     * @property \App\Model\Entity\Smsledger $smslTbl Description
     * @param type $contactno
     * @param type $message
     * @param type $clientId
     * @param string $senderid
     * @param type $comment
     * @param type $customer_id
     * @param type $store_id
     * @return type
     */
    public function smsgshup($contactno = NULL, $message = NULL, $clientId = NULL, $senderid = "ZAKOPI",$comment = "", $customer_id = 0, $store_id = 0,$extra = "") {
        $multiplier = 1;
        if($senderid == "" || $senderid == null || $senderid == false){
            $senderid = "ZAKOPI";
        }
        $response["apiResponse"] = $this->sendViaGupShup($contactno,$message,$senderid);
        $response["costCalc"] = $this->detect($message);
        $response['status'] = 400;
        
        $gshupTbl = \Cake\ORM\TableRegistry::get('Gshuplogs');
        
        if (preg_match_all("/success/im", $response["apiResponse"]) > 0 && $clientId) {
            
            /** GshupLogs Generator **/
            $extId1 = explode("|", $response["apiResponse"]);
            $extId = trim($extId1[2]);
            $extras = "";
            if($clientId){
                $extras = "Number:".$contactno;
                $extras .= "|ClientID:".@$clientId;
                $extras .= "|Msg:".$message;
                
            }
            $gshupData = [
                "external_id" => $extId,
                "status" => trim($extId1[0]),
                "created"   => time(),
                "extras" => $extras
            ];
            $gEnt = $gshupTbl->newEntity($gshupData);
            $glog = $gshupTbl->save($gEnt);
            /** GshupLogs Generator end **/
            
            
            $smslTbl = \Cake\ORM\TableRegistry::get('Smsledger');
            $smslTbl->addDebit($response["costCalc"]["smsCount"],@$clientId,$comment,$customer_id,$store_id,$glog->id."|".$extra,$contactno);
            
            $response['status'] = 200;
            $response['response']['units'] = $response["costCalc"]["smsCount"];
            $multiplier = $response["costCalc"]["smsCount"];
            $clientsTbl = \Cake\ORM\TableRegistry::get('Clients');
            $res = $clientsTbl->updateAll([
                'sms_sent = sms_sent + ' . $multiplier, "sms_quantity = sms_quantity - $multiplier"
                    ], ['id' => $clientId]);
        }else{
            /** GshupLogs Generator **/
            $extId1 = explode("|", $response["apiResponse"]);
            $extId = trim(@$extId1[2]);
            $extras = "";
            
            $gshupData = [
                "external_id" => $extId,
                "status" => trim($extId1[0]),
                "created"   => time(),
                "extras" => $response["apiResponse"]
            ];
            $gEnt = $gshupTbl->newEntity($gshupData);
            $gshupTbl->save($gEnt);
            /** GshupLogs Generator end **/
        }
        return $response;     
    }
   
    /**
     * Method to send SMS via Gupshup
     * @property \App\Model\Entity\Smsledger $smslTbl Description
     * @param type $contactno
     * @param type $message
     * @param type $clientId
     * @param string $senderid
     * @param type $comment
     * @param type $customer_id
     * @param type $store_id
     * @return type
     */
    public function smsgshupCampaign($contactno = NULL, $message = NULL, $clientId = NULL, $senderid = "ZAKOPI",$comment = "", $customer_id = 0, $store_id = 0,$extra = "") {
        $multiplier = 1;
        if($senderid == "" || $senderid == null || $senderid == false){
            $senderid = "ZAKOPI";
        }
        $response["apiResponse"] = $this->sendViaGupShup($contactno,$message,$senderid);
        $response["costCalc"] = $this->detect($message);
        $response['status'] = 400;
        
        $gshupTbl = \Cake\ORM\TableRegistry::get('Gshuplogs');
        
        if (preg_match_all("/success/im", $response["apiResponse"]) > 0 && $clientId) {
            
            /** GshupLogs Generator **/
            $extId1 = explode("|", $response["apiResponse"]);
            $extId = trim($extId1[2]);
            $extras = "";
            if($clientId){
                $extras = "Number:".$contactno;
                $extras .= "|ClientID:".@$clientId;
                $extras .= "|Msg:".$message;
                
            }
            $gshupData = [
                "external_id" => $extId,
                "status" => trim($extId1[0]),
                "created"   => time(),
                "extras" => $extras
            ];
            $gEnt = $gshupTbl->newEntity($gshupData);
            $glog = $gshupTbl->save($gEnt);
            
            $response['status'] = 200;
            $response['response']['units'] = $response["costCalc"]["smsCount"];
            $multiplier = $response["costCalc"]["smsCount"];
            $clientsTbl = \Cake\ORM\TableRegistry::get('Clients');
            $res = $clientsTbl->updateAll([
                'sms_sent = sms_sent + ' . $multiplier, "sms_quantity = sms_quantity - $multiplier"
                    ], ['id' => $clientId]);
        }else{
            /** GshupLogs Generator **/
            $extId1 = explode("|", $response["apiResponse"]);
            $extId = trim(@$extId1[2]);
            $extras = "";
            
            $gshupData = [
                "external_id" => $extId,
                "status" => trim($extId1[0]),
                "created"   => time(),
                "extras" => $response["apiResponse"]
            ];
            $gEnt = $gshupTbl->newEntity($gshupData);
            $gshupTbl->save($gEnt);
            /** GshupLogs Generator end **/
        }
        return $response;     
    }
   
    
    
    private function sendViaGupShup($send_to=null,$msg="",$mask="ZAKOPI"){
        $msg = str_replace(" Optout sms OPTZKP to 9220092200", "", $msg);
        $msg .= " Optout sms OPTZKP to 9220092200";
        
        $request =""; //initialise the request variable
        $param["method"]= "sendMessage";
        $param["send_to"] = $send_to;
        $param["msg"] = $msg;
        $param["userid"] = "2000153962";
        $param["password"] = "azvvo26Vb";
        $param["mask"] = $mask;
//        $param[mask] = "KRASNS";
        $param["v"] = "1.1";
        $r = $this->detect($msg);
        $param["msg_type"] = $r['smsType']; //Can be "FLASHâ€�/"UNICODE_TEXT"/â€�BINARYâ€�
        $param["auth_scheme"] = "PLAIN";
        //Have to URL encode the values
        foreach($param as $key=>$val) {
            $request.= $key."=".urlencode($val);
            //we have to urlencode the values
            $request.= "&";
            //append the ampersand (&) sign after each parameter/value pair
        }
        $request = substr($request, 0, strlen($request)-1);
        //remove final (&) sign from the request
        $url =
        "http://enterprise.smsgupshup.com/GatewayAPI/rest?".$request;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        return $curl_scraped_page;
    }
    
    public function detect($v = "") {
        $v = str_replace(" Optout sms OPTZKP to 9220092200", "", $v);
        
        
        $v = str_replace(array("\n", "\r\n", "\r"),"",$v);
//        $v = preg_replace("/[\r\n]+/", "\n", $v);
        
        $cnSrtCd = preg_match_all('/\\{\\{\\w+}}/i', $v);
        $v = preg_replace('/\\{\\{\\w+}}/i', "", $v);
        $unicodeCount = preg_match_all('/([\p{Devanagari}])/imu', $v);
        $res = [];
        $res['shortCodesCount'] = $cnSrtCd;
        $res['unicodeCount'] = $unicodeCount;
        $res['smsCharCount'] = (mb_strlen($v,'utf-8') + ($cnSrtCd * 15));
        $smsCount = 0;
        if($unicodeCount > 0){
            $smsCount = 1 + ceil(($res['smsCharCount'] - (70 - 32)) / 66);
            $res['smsType'] = "UNICODE_TEXT";
        }else{
            $smsCount = 1 + ceil(($res['smsCharCount'] - (160 - 32)) / 153);
            $res['smsType'] = "TEXT";
        }
        $res['smsCount'] = $smsCount;
//        pr($res); exit;
        return $res;
    }

    public function plivoresponse($id = NULL) {
        require_once(ROOT . DS . 'webroot' . DS . 'plivo.php');
        $auth_id = "MAMZVKMDGXYWVLZME4YT";
        $auth_token = "ZjcxNjU1MTBhYjM5YzJiM2RmOWIxNDZiZTkzOTBk";
        $p = new \RestAPI($auth_id, $auth_token);
        $params = array('record_id' => $id);
        $response = $p->get_message($params);
        return $response;
    }

    public function sendnewemail($clientemail = NULL, $messagecontent = NULL, $subject = NULL) {
        $clientsTbl = \Cake\ORM\TableRegistry::get('Clients');
        $clientInfo = $clientsTbl->find()->select(['email', 'name'])->hydrate(FALSE)->where(['email' => $clientemail])->first();
        $this->request->data['fromemail'] = 'business@zakoopi.com';
        $this->request->data['toemail'] = $clientemail;
        $this->request->data['name'] = $clientInfo['name'];
        $this->request->data['company'] = "Zakoopi";
        $from_name = "Zakoopi For Business";
        
        if (!$subject) {
            $subject = "Welcome to Zakoopi for Business";
        }
        $res = $this->_sendEmail([
            $this->request->data['toemail'] => $this->request->data['name'],
            'support@zakoopi.com' => "Business-Zakoopi Added New Client $clientemail",
            'business@zakoopi.com' => 'Zakoopi Business'
        ], $subject, $messagecontent, 'Business-Welcome-Zakoopi', [
            'firstname'=> $clientInfo['name']
        ]);
        return $res; 
        
        try {
            $mandrill = new \Mandrill('OVRHlxZwdDeqA_BtNvrVsw');
            $template_name = 'Business-Welcome-Zakoopi';
            $template_content = array(
                array(
                    'name' => 'firstname',
                    'content' => $clientInfo['name'],
                ),
                array(
                    'name' => 'content',
                    'content' => $messagecontent,
                )
            );
            if (!$subject) {
                $subject = "Welcome to Zakoopi for Business";
            }
            $message = array(
                'html' => '',
                'text' => '',
                'subject' => $subject,
                'from_email' => $this->request->data['fromemail'],
                'from_name' => $from_name,
                'to' => array(
                    array(
                        'email' => $this->request->data['toemail'],
                        'name' => $this->request->data['name'],
                        'type' => 'to'
                    ),
                    array(
                        "email" => "support@zakoopi.com",
                        "name" => "Business-Zakoopi Added New Client $clientemail",
                        "type" => "bcc"
                    )
                ),
                'headers' => array('Reply-To' => 'business@zakoopi.com'),
                'important' => false,
                'track_opens' => null,
                'track_clicks' => null,
                'auto_text' => null,
                'auto_html' => null,
                'inline_css' => null,
                'url_strip_qs' => null,
                'preserve_recipients' => null,
                'view_content_link' => null,
                'bcc_address' => 'business@zakoopi.com',
                'tracking_domain' => null,
                'signing_domain' => null,
                'return_path_domain' => null,
                'merge' => true,
                'merge_language' => 'mailchimp',
                'global_merge_vars' => array(
                    array(
                        'name' => 'merge1',
                        'content' => 'merge1 content'
                    )
                ),
                'merge_vars' => array(
                    array(
                        'rcpt' => 'business@zakoopi.com',
                        'vars' => array(
                            array(
                                'name' => 'merge2',
                                'content' => 'merge2 content'
                            )
                        )
                    )
                )
            );
            $async = false;
            $ip_pool = 'Main Pool';
            $send_at = 'example send_at';
            $result = $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async, $ip_pool);
        } catch (Mandrill_Error $e) {
            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            throw $e;
        }
        
        return $result;
    }

    public function sendemail($useremail = NULL, $messagecontent = NULL, $clientId = null, $subject = NULL, $store_id = NULL) {
        $clientsTbl = \Cake\ORM\TableRegistry::get('Clients');
        $clientEmail = $clientsTbl->find()->select(['email', 'name'])->hydrate(FALSE)->where(['id' => $clientId])->first();

        $customerTbl = \Cake\ORM\TableRegistry::get('Customers');
        $customerInfo = $customerTbl->find()->select(['name'])->hydrate(false)->where(['email' => $useremail, 'name !=' => ''])->first();

        $storesTbl = \Cake\ORM\TableRegistry::get('Stores');
        $storeInfo = $storesTbl->find()->select(['name', 'address'])->where(['id' => $store_id])->hydrate(false)->first();

        if (!$customerInfo) {
            $customerInfo['name'] = " ";
        }
        $this->request->data['fromemail'] = $clientEmail['email'];
        $this->request->data['toemail'] = $useremail;
        $this->request->data['name'] = $customerInfo['name'];
        $this->request->data['company'] = "Zakoopi";
        $from_name = "business.zakoopi.com";
        if ($storeInfo['name']) {
            $from_name = $storeInfo['name'];
        }
        if (!$subject) {
            $subject = "Zakoopi";
        }
        
        $res = $this->_sendEmail([
            $this->request->data['toemail'] => $this->request->data['name']
        ], $subject, $messagecontent, 'Business-Email-Campaign',[
            $this->request->data['fromemail'] => $from_name
        ]);
        
        return $res;
        
        
        
        try {
            $mandrill = new \Mandrill('OVRHlxZwdDeqA_BtNvrVsw');
            $template_name = 'Business-Email-Campaign';
            $template_content = array(
                array(
                    'name' => 'content',
                    'content' => $messagecontent,
                )
            );
            if (!$subject) {
                $subject = "Zakoopi";
            }
            $message = array(
                'html' => '',
                'text' => '',
                'subject' => $subject,
                'from_email' => $this->request->data['fromemail'],
                'from_name' => $from_name,
                'to' => array(
                    array(
                        'email' => $this->request->data['toemail'],
                        'name' => $this->request->data['name'],
                        'type' => 'to'
                    )
                ),
                'headers' => array('Reply-To' => $clientEmail['email']),
                'important' => false,
                'track_opens' => null,
                'track_clicks' => null,
                'auto_text' => null,
                'auto_html' => null,
                'inline_css' => null,
                'url_strip_qs' => null,
                'preserve_recipients' => null,
                'view_content_link' => null,
//                'bcc_address' => $clientEmail['email'],
                'tracking_domain' => null,
                'signing_domain' => null,
                'return_path_domain' => null,
                'merge' => true,
                'merge_language' => 'mailchimp',
                'global_merge_vars' => array(
                    array(
                        'name' => 'merge1',
                        'content' => 'merge1 content'
                    )
                ),
                'merge_vars' => array(
                    array(
                        'rcpt' => $clientEmail['email'],
                        'vars' => array(
                            array(
                                'name' => 'merge2',
                                'content' => 'merge2 content'
                            )
                        )
                    )
                )
            );
            $async = false;
            $ip_pool = 'Main Pool';
            $send_at = 'example send_at';
            $result = $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async, $ip_pool);
        } catch (Mandrill_Error $e) {
            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            throw $e;
        }
        if ($result && $clientId) {
            $res = $clientsTbl->updateAll([
                'email_sent = email_sent + 1', 'email_left = email_left - 1'
                    ], ['id' => $clientId]);
        }
        return $result;
    }

    /**
     *  Mandrill Get Email Deatil
     * @param Sting $id
     * @return Arary
     * @throws \App\Common\Mandrill_Error
     */
    public function mandrillresponse($id = NULL) {
        try {
            $mandrill = new \Mandrill('OVRHlxZwdDeqA_BtNvrVsw');
            $result = $mandrill->messages->info($id);
            return $result;
        } catch (Mandrill_Error $e) {
            // Mandrill errors are thrown as exceptions
            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            // A mandrill error occurred: Mandrill_Unknown_Message - No message exists with the id 'McyuzyCS5M3bubeGPP-XVA'
            throw $e;
        }
    }

    /**
     * IOS Push Notifications
     * @param String $tToken
     * @param String $storeName
     * @param String $message
     * @param String $image
     * @return Array
     */
    public function iospush($tToken = NULL, $title = NULL, $message, $image = 0,$certificate = 'pushcert.pem',$certPass = '12345') {
//        $tHost = 'gateway.sandbox.push.apple.com';
        $tHost = 'gateway.push.apple.com';
        $tPort = 2195;
        $tCert = WWW_ROOT . 'iosPush/'.$certificate;
        $tPassphrase = $certPass;
//        $tToken = '0a850913689cd125bff3b755cb252c271b141d29898d529b4280b4b31c14981b';
        $tAlert['title'] = $title;
        $tAlert['message'] = $message;
        $tAlert['image'] = $image;
        $tBadge = 1;
        $tSound = 'default';
        $tPayload = $message;
        $tBody['aps'] = array(
            'alert' => $message,
            'badge' => $tBadge,
            'sound' => $tSound,
        );
        $tBody ['payload'] = $message;
        $tBody = json_encode($tBody);
        $tContext = stream_context_create();
        stream_context_set_option($tContext, 'ssl', 'local_cert', $tCert);
        stream_context_set_option($tContext, 'ssl', 'passphrase', $tPassphrase);
        $tSocket = stream_socket_client('ssl://' . $tHost . ':' . $tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $tContext);
        if (!$tSocket){
            $response['error'] =  1;
            $response['msg'] =  "APNS Connection Failed: $error $errstr" . PHP_EOL;
            return $response;
        }
        $tMsg = chr(0) . chr(0) . chr(32) . pack('H*', $tToken) . pack('n', strlen($tBody)) . $tBody;
        $tResult = fwrite($tSocket, $tMsg, strlen($tMsg));
        if ($tResult){
            $response['error'] =  0;
            $response['msg'] =  'Delivered Message to APNS' . PHP_EOL;
        }
        else{
            $response['error'] =  1;
            $response['msg'] = 'Could not Deliver Message to APNS' . PHP_EOL;
        }
        fclose($tSocket);
        return $response;
    }
    /**
     * Android Push Notifications
     * @param String $diviceid
     * @param String $storename
     * @param String $message1
     * @param String $image
     * @return Array
     */
    public function gcmNotification($diviceid=null,$storename,$message1=null,$image = 0, $apiKey = 'AIzaSyD9PHWUswNEx_CayMT3vccA1mFTCWSvJPg') {
            $registrationIDs = array($diviceid);
            $msg = [ 
                        'storename' => $storename,
                        'message'   => $message1,
                        'image'     => $image
                    ];
            $url = 'https://android.googleapis.com/gcm/send';
            $fields = array(
                'registration_ids' => $registrationIDs,
                'data' => $msg,
            );
            $headers = array(
                'Authorization: key=' . $apiKey,
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
    //        print_r($result);
            return $result;
    }
    
    
    public function _sendEmail($to,$subject,$content, $template = null, $vars = [], $from = ['alerts@zakoopi-alerts.com' => 'Team ZAKOOPI']){
        if($template){
           $finalContent = file_get_contents(WWW_ROOT.'emailTmpls'.DS.$template);
           if(is_array($vars)){
               foreach ($vars as $k => $v){
                   $finalContent = str_replace('{{'.$k.'}}', $v, $finalContent);
               }
           }
           $finalContent = str_replace('{{content}}', $content, $finalContent);
        }else{
            $finalContent = $content;
        }
	$toEm = [];
	foreach($to as $k => $v){
	    if($v != ''){
		$toEm[$k] = $v;
            }else{
		$toEm[$k] = $k;
            }
	}
	$to = $toEm;
        $email = new Email('elastic');
        $res = $email->from($from)
            ->to($to)
            ->emailFormat('html')
            ->subject($subject)
            ->replyTo('business@zakoopi.com', 'Zakoopi Business')
            ->send($finalContent);
        return $res;
    }
    
}
