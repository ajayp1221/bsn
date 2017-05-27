<?php

namespace App\Controller;

use App\Controller\AppController;

class WappController extends AppController {

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
        \Cake\Core\Configure::write('debug', true);
    }

    var $_client;
    var $_pw = 'SMmaKY4OAvjpVCyN91v/B8g98go=';
    var $_un = '918750784618';
    var $_nick = "Himanshu Verma";

    public function reg($code = null) {
        exit;

        $debug = true;
        // Create a instance of Registration class.
        $r = new \Registration($this->_un, $debug);
        if ($code) {
            $x = $r->codeRegister($code);
        } else {
            $x = $r->codeRequest('sms'); // could be 'voice' too
        }

        pr($x);
        exit;
    }

    public function sendmsg() {

        if ($this->request->is(['post', 'put'])) {

            $d = $this->request->data;
            $nums = explode(',', $d['nums']);
            $msg = $d['msg'];

            // Create a instance of WhastPort.
            $w = new \WhatsProt($this->_un, $this->_nick, false);

            $w->connect(); // Connect to WhatsApp network
            $w->loginWithPassword($this->_pw); // logging in with the password we got!
//            $w->sendGetPrivacyBlockedList(); // Get our privacy list [Done automatically by the API]
//            $w->sendGetClientConfig(); // Get client config [Done automatically by the API]
//            $w->sendGetServerProperties(); // Get server properties [Done automatically by the API]
//            $w->sendGetGroups(); // Get groups (participating)
//            $w->sendGetBroadcastLists(); // Get broadcasts lists
//            
            $res = [];
            foreach ($nums as $num){
                $res[] = $w->sendMessage($num , $msg);
            }
            $res[] = $w->sendBroadcastMessage($nums, $msg);
            debug($res);
            exit;
        } else {
            echo '<form method="post">';
            echo 'Message:<textarea name="msg" /></textarea><hr>';
            echo 'Numbers:<textarea name="nums" /></textarea><hr>';
            echo '<input type="submit" value="send">';
            echo '</form>';
            exit;
        }
    }

}
