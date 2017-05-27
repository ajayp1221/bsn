<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;

/**
 * Test Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 */
class TestController extends AppController {

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['switchme', 'smsledgersetup', 'iosTest', 'ses', 'googleContactApi', 'te', 'mg']);
//        exit;
    }

    public function mg() {
        \Cake\Core\Configure::write('debug', true);
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $t1 = time();
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
//        $csts = $cstTbl->find()->hydrate(false)->all()->toArray();
        $client = new \MongoDB\Client("mongodb://localhost:27017");
        $collection = $client->bsn->customers;

//        $result = $collection->insertMany($csts);

        $result = $collection->find([],
        [
            'limit' => 2000,
            'sort' => ['pop' => -1],
        ]);
        echo '<pre>';
        foreach ($result as $entry) {
            echo $entry['_id'], ': ', $entry['contact_no'], "\n";
        }
        echo "||" . (time() - $t1);
        exit;
    }

    public function iosTest($store_id, $tToken = NULL) {
        \Cake\Core\Configure::write('debug', true);
        $methods = new \App\Common\Methods();
        $store_id = md5($store_id) . '.pem';
        $res = $methods->iospush($tToken, 'Kamal', 'This is for ios testing', 0, $store_id, 12345);
        debug($res);
        exit;
    }

    public function srturl() {
        $googl = new \Sonrisa\Service\ShortLink\Google('AIzaSyAzC7nmB9Y2shrUBg2749_AYwOpBQhxGlY');
        echo $url = $googl->shorten('http://www.zakoopi.com/delhi-ncr/zara-dlf-promenade-mall-delhi-ncr');
        exit;
    }

    public function t() {
        $a = new \App\Common\Methods();
        print_r($a->plivo("8750784618", 'Test MSG', 2));
        exit;
        $_s3Config = [
            'version' => 'latest',
            'region' => 'ap-southeast-1',
            'debug' => false,
            'credentials' => [
                'key' => 'AKIAJRXXNLWTSUENKULA',
                'secret' => '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'
            ]
        ];

        $s3 = new \Aws\S3\S3Client($_s3Config);
        $itms = $s3->getIterator('ListObjects', ['Bucket' => 'bsn-zkp']);
        $result = $s3->putObject([
            'Bucket' => 'tstzkp',
            'Key' => 'favicon.png',
            'Body' => fopen(WWW_ROOT . 'favicon.png', 'r'),
            'ACL' => 'public-read',
        ]);
        pr($result);
        exit;
    }

    public function switchme($id = null) {
        if ($id) {
            $id = base64_decode(base64_decode(base64_decode($id)));
            $tbl = \Cake\ORM\TableRegistry::get('Clients');
            $usr = $tbl->get($id)->toArray();
            $this->Auth->setUser($usr);
            $this->redirect("/");
        }
    }

    public function msms() {
        echo APP . 'bsms' . DS;
        exit;
//        try
//		{
//			UnifiedAccount unifiedAccount = new UnifiedAccount();
//			unifiedAccount.setAccountSid(accountSid);
//			unifiedAccount.setPassword(password);
//	
//			UnifiedMessage unifiedMessage = new UnifiedMessage();
//			unifiedMessage.setAccountId("Set ur generated account Sid here");
//			// Message set To No 
//			unifiedMessage.setTo("XXXXXXXXXX");
//			// Message set To Body 
//			unifiedMessage.setBody("Test Message From Webaroo");
//			unifiedMessage.setCreatedOn(new Date());
//			// Message From
//			unifiedMessage.setFrom("XXXXXXXXXX");
//			unifiedMessage.addMessageType(MessageTypes.SMS);
//			unifiedMessage.addMessageType(MessageTypes.DATA);
//	
//			UnifiedRequest unifiedRequest = new UnifiedRequest();
//			unifiedRequest.setBaseUri(properties.getProperty("base_url"));
//			unifiedRequest.setResource(properties.getProperty("sms_resource_path"));
//			unifiedRequest.setContentType(ContentType.APPLICATION_JSON);
//			unifiedRequest.setMessage(unifiedMessage);
//			unifiedRequest.setHttpMethod(HttpMethod.POST);
//			UnifiedResponse response = UnifiedRestClient.sendRequest(unifiedAccount, unifiedRequest);
//		}
//		catch (UnifiedRestException e)
//		{
//			e.printStackTrace();
//		}
    }

    /**
     * 
     */
    public function smsgupshup() {
        $request = ""; //initialise the request variable
        $param[method] = "sendMessage";
        $param[send_to] = "8750784618";
        $param[msg] = "This is a test message.";
        $param[userid] = "2000153962";
        $param[password] = "azvvo26Vb";
        $param[mask] = "KRASNS";
        $param[v] = "1.1";
        $param[msg_type] = "TEXT"; //Can be "FLASH”/"UNICODE_TEXT"/”BINARY”
        $param[auth_scheme] = "PLAIN";
        //Have to URL encode the values
        foreach ($param as $key => $val) {
            $request.= $key . "=" . urlencode($val);
            //we have to urlencode the values
            $request.= "&";
            //append the ampersand (&) sign after each parameter/value pair
        }
        $request = substr($request, 0, strlen($request) - 1);
        //remove final (&) sign from the request
        $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?" . $request;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        echo $curl_scraped_page;
        exit;
    }

    public function datalog() {
        $d = $this->request->data;
        debug(json_decode($d['json'], true));
        exit;
    }

    public function detect($v = "") {

        $cnSrtCd = preg_match_all('/\\{\\{\\w+}}/i', $v);
        $v = preg_replace('/\\{\\{\\w+}}/i', "", $v);
        $unicodeCount = preg_match_all('/([\p{Devanagari}])/imu', $v);
        $res = [];
        $res['shortCodesCount'] = $cnSrtCd;
        $res['unicodeCount'] = $unicodeCount;
        $res['smsCharCount'] = (mb_strlen($v) + ($cnSrtCd * 15));
        $smsCount = 0;
        if ($unicodeCount > 0) {
            $smsCount = 1 + ceil(($res['smsCharCount'] - 70) / 66);
            $res['smsType'] = "UNICODE_TEXT";
        } else {
            $smsCount = 1 + ceil(($res['smsCharCount'] - 160) / 153);
            $res['smsType'] = "TEXT";
        }
        $res['smsCount'] = $smsCount;
        pr($res);
        exit;
        return $res;
    }

    private function sendLeadSms($name = "", $source = "JustDial", $number = null) {
        if ($number == null) {
            return false;
        }
        $methods = new \App\Common\Methods();
        $msg = "Hi " . $name . ", in respect of your recent query on " . $source . ", we at SDR would like to offer our services. Regards, 7838881003, https://goo.gl/JqtJ8R";
        $number = preg_replace("/(\\+91)/", "", $number);
        return $methods->plivo($number, $msg, 11, "SROYAL");
    }

    public function jdimap($page = 1) {
        \Cake\Core\Configure::write('debug', true);
        $mailbox = new \PhpImap\Mailbox('{imap.gmail.com:993/imap/ssl}SROYAL', 'demo@zakoopi.com', 'demo@1234', __DIR__);
        $mails = array();
        $mailsIds = $mailbox->searchMailbox('SUBJECT "justdial" UNSEEN');
        if (!$mailsIds) {
            die('Mailbox is empty');
        }

        $data = [];
        require ROOT . '/vendor/niels/ganon/src/Ganon.php';
        foreach ($mailsIds as $mailId) {
            $mail = $mailbox->getMail($mailId);
            $html = str_get_dom($mail->textHtml);

            $xyz = $html('tbody', 1);
            $xy = $xyz('tr', 1);
            $dt['caller_name'] = $xy('td', 1)->getPlainText();
            $xy = $xyz('tr', 2);
            $dt['caller_requirment'] = $xy('td', 1)->getPlainText();
            $xy = $xyz('tr', 6);
            $dt['caller_phone'] = $xy('td', 1)->getPlainText();
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

    public function jdservicesimap($page = 1) {
        \Cake\Core\Configure::write('debug', true);
        $mailbox = new \PhpImap\Mailbox('{imap.gmail.com:993/imap/ssl}SROYAL', 'demo@zakoopi.com', 'demo@1234', __DIR__);
        $mails = array();
        $mailsIds = $mailbox->searchMailbox('FROM "just dial services" UNSEEN');
        if (!$mailsIds) {
            die('Mailbox is empty');
        }

        $data = [];
        require ROOT . '/vendor/niels/ganon/src/Ganon.php';
        foreach ($mailsIds as $mailId) {
            $mail = $mailbox->getMail($mailId);
            if (preg_match("/VN Number/", $mail->subject)) {
                continue;
            }
            $html = str_get_dom($mail->textHtml);

            $xyz = $html('table', 1);
            $xy = $xyz('tr', 1);
            $dt['caller_name'] = explode(" from", $xy('td', 1)->getPlainText())[0];
            $xy = $xyz('tr', 2);
            $dt['caller_requirment'] = $xy('td', 1)->getPlainText();
            $xy = $xyz('tr', 6);
            $dt['caller_phone'] = explode(", ", $xy('td', 1)->getPlainText());
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

    public function metroimap($page = 1) {

        \Cake\Core\Configure::write('debug', true);
        $mailbox = new \PhpImap\Mailbox('{imap.gmail.com:993/imap/ssl}SROYAL', 'demo@zakoopi.com', 'demo@1234', __DIR__);
        $mails = array();
        $mailsIds = $mailbox->searchMailbox('FROM "MatrimonyDirectory" UNSEEN');
        if (!$mailsIds) {
            die('Mailbox is empty');
        }

        $data = [];
        require ROOT . '/vendor/niels/ganon/src/Ganon.php';
        foreach ($mailsIds as $mailId) {
            $mail = $mailbox->getMail($mailId);

            if (preg_match("/VN Number/", $mail->subject)) {
                continue;
            }
//            pr($mail->textHtml); exit;
            $html = str_get_dom($mail->textHtml);

            $xyz = $html('table', 4);
            $xy = $xyz('tr', 0);
            $dt['caller_name'] = $xy('td', 1)->getPlainText();
            $xy = $xyz('tr', 1);
            $dt['caller_phone'] = explode(": ", $xy('td', 1)->getPlainText());
            $data[] = [
                "date" => $mail->date,
                "subject" => $mail->subject,
                "fromAddress" => $mail->fromAddress,
                //"textHtml" => trim($xyz),
                "data" => $dt
            ];
            $this->sendLeadSms($dt['caller_name'], "MatrimonyDirectory", $dt['caller_phone'][1]);
        }
//        $this->set("data",$data);
//        $this->set("_serialize",['data']);
        exit;
    }

    public function getsmstime() {
        $t1 = time();
        $request = ""; //initialise the request variable
        $param["method"] = "sendMessage";
        $param["send_to"] = '8860641616';
        $param["msg"] = "Message to track Time Log for sending sms via gupshup.";
        $param["userid"] = "2000153962";
        $param["password"] = "azvvo26Vb";
        $param["mask"] = 'ZAKOOPI';
//        $param[mask] = "KRASNS";
        $param["v"] = "1.1";
        $param["msg_type"] = 'TEXT'; //Can be "FLASHâ€�/"UNICODE_TEXT"/â€�BINARYâ€�
        $param["auth_scheme"] = "PLAIN";
        //Have to URL encode the values
        foreach ($param as $key => $val) {
            $request.= $key . "=" . urlencode($val);
            //we have to urlencode the values
            $request.= "&";
            //append the ampersand (&) sign after each parameter/value pair
        }
        $request = substr($request, 0, strlen($request) - 1);
        //remove final (&) sign from the request
        $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?" . $request;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);

        $t2 = time();
        $r = [
            "startTime" => date('c', $t1),
            "endTime" => date('c', $t2),
            "timediff" => $t2 - $t1,
            "exp" => $t2 . " - " . $t1 . " = " . ($t2 - $t1)
        ];
        echo '<pre>';
        print_r($r);
        exit;
    }

    /**
     * set Storesettings entries for each 
     * create-str-set
     */
    public function createStrSet() {
        $strTbl = \Cake\ORM\TableRegistry::get('Stores');
        $settTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $stores = $strTbl->find()->all();
        foreach ($stores as $store) {
            $entities = $settTbl->newEntities([
                [
                    'store_id' => $store->id,
                    'meta_key' => 'r-screen-customer-discount',
                    'meta_value' => '0'
                ],
                [
                    'store_id' => $store->id,
                    'meta_key' => 'r-screen-customer-discount-msg',
                    'meta_value' => 'Thank you for promoting us.'
                ],
                [
                    'store_id' => $store->id,
                    'meta_key' => 's-screen-customer-discount',
                    'meta_value' => '0'
                ],
                [
                    'store_id' => $store->id,
                    'meta_key' => 's-screen-customer-discount-msg',
                    'meta_value' => 'Thank you for promoting us on social media.'
                ],
            ]);
            foreach ($entities as $ent) {
                $settTbl->save($ent);
            }
        }
        exit;
    }

    public function fixDob() {
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
        $recs = $cstTbl->find()->where([
            'Customers.store_id' => 34,
            'Customers.added_by' => 'CSV',
            'Customers.dob LIKE' => '%-%',
        ]);
        $res = [];
        foreach ($recs as $r) {
            $dob = explode('-', $r->dob);
            $r->dob = $dob[1] . "/" . $dob[0] . "/" . $dob[2];
            $res[] = $cstTbl->save($r);
        }
        $this->set('recs', $res);
        $this->set('_serialize', ['recs']);
    }

    public function smsledgersetup2() {
        $cliTbl = \Cake\ORM\TableRegistry::get('Clients');
        $clients = $cliTbl->find()->all();
        $smslTbl = \Cake\ORM\TableRegistry::get('Smsledger');
        foreach ($clients as $client) {
            $smslEnt = $smslTbl->newEntity([
                'client_id' => $client->id,
                'comments' => 'Balance as on 8th April 2016',
                'credit' => $client->sms_quantity,
                'debit' => 0,
                'balance' => $client->sms_quantity,
            ]);
            $smslTbl->save($smslEnt);
        }
//        exit;
    }

    public function smsledgersetup() {
        $cliTbl = \Cake\ORM\TableRegistry::get('Clients');
        $clients = $cliTbl->find()->all();
        //$smslTbl = \Cake\ORM\TableRegistry::get('Smsledger');
        $cn = \Cake\Datasource\ConnectionManager::get('default');
        $cn->begin();
        foreach ($clients as $client) {
            $cn->insert('smsledger', [
                'client_id' => $client->id,
                'comments' => 'Balance as on 8th April 2016',
                'credit' => $client->sms_quantity,
                'debit' => 0,
                'balance' => $client->sms_quantity,
                'created' => time()
            ]);
        }
        $cn->commit();
//        $cn->rollback();
//        exit;
    }

    /**
     * @property \App\Model\Entity\Smsledger $smslTbl Description
     */
    public function testtran() {

        $smslTbl = \Cake\ORM\TableRegistry::get('Smsledger');
//        $x  = $smslTbl->addCredit(20,2,"From Dashboard...");
        $x = $smslTbl->addDebit(1, 2, "Sent Daily Report MSG...");
        pr($x);
        exit;
    }

    /**
     * AWS SES Test Function
     * 
     */
    public function ses() {
        \Cake\Core\Configure::write('default', true);
        // Setup Transport
        Email::configTransport('aws_ses', [
            'host' => 'email-smtp.us-west-2.amazonaws.com',
            'port' => 25,
            'username' => 'AKIAIKTYB6UC7RBXLJ2A',
            'password' => 'AkBB5OaDj/7eoC3UTFN6xLEduUwwU4II+Pz+tFBskAJQ',
            'className' => 'Smtp'
        ]);

        $email = new Email('aws_ses');
        $res = $email->from(['himanshu.verma@zakoopi.com' => 'Himanshu - Team ZAKOOPI'])
                ->to('himan.verma@live.com')
                ->subject('About')
                ->send('My message');

        pr($res);
        exit;
    }

    private function createContact($access_token) {
        $client = new \Cake\Network\Http\Client([
            'ssl_verify_peer' => false
        ]);
        $xmlString = "<atom:entry xmlns:atom='http://www.w3.org/2005/Atom' xmlns:gd='http://schemas.google.com/g/2005'>
                                    <atom:category scheme='http://schemas.google.com/g/2005#kind'
                                                   term='http://schemas.google.com/contact/2008#contact'/>
                                   <atom:title>Abu Andabekov</atom:title>
                                    <gd:phoneNumber rel='http://schemas.google.com/g/2005#work'
                                                    primary='true'>
                                        87016538609
                                    </gd:phoneNumber>
                                    <gd:email rel='http://schemas.google.com/g/2005#work'
                                    primary='true'
                                    address='liz@gmail.com' displayName='E. Bennet'/>
                                 </atom:entry>";

        //pr($access_token); exit;
        $response = $client->post(
                'https://www.google.com/m8/feeds/contacts/default/full?access_token=' . $access_token['access_token'], (string) $xmlString, ['headers' => ['Content-Type' => 'application/atom+xml']]
        );

        return $response->body();
    }

    public function googleContactApi($val = null) {
        $session = $this->request->session();
        \Cake\Core\Configure::write('debug', true);
        $client = new \Google_Client();
        $client->setApplicationName('BusinessZKP');
        $client->setClientId("64838761597-ni4qles5it3rdr4hs804o84lae2c3cfh.apps.googleusercontent.com");
        $client->setClientSecret("SGW53uaOVAhl0B1V0RreCoPY");
        $client->setRedirectUri(\Cake\Routing\Router::url('/test/googleContactApi/authdone', true));
        $client->addScope([
            \Google_Service_Plus::PLUS_LOGIN,
            \Google_Service_People::CONTACTS,
            \Google_Service_People::CONTACTS_READONLY
        ]);
        $client->setAccessType('offline');

        if ($session->check('GAccessToken')) {
            $client->setAccessToken($session->read('GAccessToken'));
            $contaceService = new \Google_Service_People($client);
            $contactList = $contaceService->people_connections->listPeopleConnections('people/me', array('pageSize' => 100))->getConnections();
            //pr(get_class_methods($contactList));

            $createdContact = simplexml_load_string($this->createContact($session->read('GAccessToken')));
            pr($createdContact);




            exit;
        } else {
            if ($val == null) {
                $client->setRedirectUri(\Cake\Routing\Router::url('/test/googleContactApi/authdone', true));
                return $this->redirect($client->createAuthUrl());
            } else {
                $code = $this->request->query('code');
                $client->authenticate($code);
                $accessToken = $client->getAccessToken();  // save in DB
                $session->write('GAccessToken', $accessToken);
                return $this->redirect('/test/googleContactApi');
            }
        }
    }

    public function recCst() {
        \Cake\Core\Configure::write('debug', TRUE);
        $this->loadModel('Customers');

        $stores = $this->Customers->Stores->find()->contain([
                    'Customers' => function($q) {
                        return $q->where(['added_by' => 'RECOMMENDED']);
                    }
                        ])->toArray();
                $this->set('stores', $stores);
                $this->set('_serialize', ['stores']);
            }

            public function te() {
                $date = date('m/d/Y H:i');
                $this->loadModel('Messages');
                $messages = $this->Messages
                        ->find()
                        ->where(['status' => 1])
                        ->matching('Campaigns', function ($q) use($date) {
                            return $q->where([
                                        "Campaigns.date_format(str_to_date(send_date,'%m/%d/%Y H:i'))" => $date
                            ]);
                        })
                        ->count();

                debug($messages);
                exit;







                $this->loadModel('Messages');
                $messages = $this->Messages
                        ->find()
                        ->where(['status' => 1])
                        ->matching('Campaigns', function ($q) use($date) {
                            return $q->where([
                                        "date_format(str_to_date(Campaigns.send_date,'%m/%d/%Y H:i'))" => $date
                            ]);
                        })
                        ->count();

                debug($messages);
                exit;
            }

        }
        