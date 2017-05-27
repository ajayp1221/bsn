<?php
namespace App\Controller;

use App\Controller\AppController;

class ElasticController extends AppController{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
        $this->loadModel('Customers', 'Elastic');
    }
    public function index(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', -1);
        
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
        $csts = $cstTbl->find()->all();
        foreach($csts as $c){
            $data = json_decode(json_encode($c),true);
            $n = $this->Customers->newEntity($data);
            $this->Customers->save($n);
        }
//        $this->Customers->deleteAll(['customers.id' => 206]);
//        $x = $this->Customers->find()->first();
//        pr($x);
        exit;
    }
    
    public function index2(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', -1);
        
//        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
//        $csts = $cstTbl->find()->all();
//        foreach($csts as $c){
//            $data = json_decode(json_encode($c),true);
//            $n = $this->Customers->newEntity($data);
//            $this->Customers->save($n);
//        }
//        $this->Customers->deleteAll(['customers.id' => 206]);
        $x = $this->Customers->find()->where(function ($builder) {
            return $builder->term('name','ajay');
        })->limit(50)->toArray();
        $this->set('data',$x);
        $this->set('_serialize',['data']);
//        exit;
    }
}