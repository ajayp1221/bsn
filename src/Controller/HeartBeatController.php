<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * HeartBeat Controller
 *
 * @property \App\Model\Table\DevicesTable $Devices
 */
class HeartBeatController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['index']);
    }

    public function index(){
        $result = $this->Auth->user('id');
        $this->set('result',$result);
        $this->set('_serialize',['result']);
    }
}