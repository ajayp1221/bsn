<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Api;

use App\Controller\Api\CommonController;
use App\Controller\Api\RecommendScreenController;
use App\Controller\Api\CustomerVisitsController;
use App\Controller\Api\NotificationsController;
use App\Controller\Api\SocialsharesController;
use App\Controller\Api\ProductcatsController;
use App\Controller\Api\AnswersController;
use App\Controller\Api\TemplatemessagesController;


/**
 * Description of DocsController
 *
 * @author Himanshu
 */
class DocsController extends AppController {

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }
   
    public function index() {
        $results = [];

        $a = new \ReflectionClass(new CommonController());
        foreach ($a->getMethods() as $method) {
            if ($a->getName() == (string)$method->class) {
                $doc = new \DocBlockReader\Reader($method->class,$method->name);
                $x = $doc->getParameters();
                if(count($x) < 4){
                    continue;
                }
                $results[(string)$method->class][$method->name] = $doc->getParameters();
            }
        }

        $a = new \ReflectionClass(new RecommendScreenController());
        foreach ($a->getMethods() as $method) {
            if ($a->getName() == (string)$method->class) {
                $doc = new \DocBlockReader\Reader($method->class,$method->name);
                $x = $doc->getParameters();
                if(count($x) < 4){
                    continue;
                }
                $results[(string)$method->class][$method->name] = $doc->getParameters();
            }
        }

        $a = new \ReflectionClass(new CustomerVisitsController());
        foreach ($a->getMethods() as $method) {
            if ($a->getName() == (string)$method->class) {
                $doc = new \DocBlockReader\Reader($method->class,$method->name);
                $x = $doc->getParameters();
                if(count($x) < 4){
                    continue;
                }
                $results[(string)$method->class][$method->name] = $doc->getParameters();
            }
        }

        $a = new \ReflectionClass(new NotificationsController());
        foreach ($a->getMethods() as $method) {
            if ($a->getName() == (string)$method->class) {
                $doc = new \DocBlockReader\Reader($method->class,$method->name);
                $x = $doc->getParameters();
                if(count($x) < 4){
                    continue;
                }
                $results[(string)$method->class][$method->name] = $doc->getParameters();
            }
        }

        $a = new \ReflectionClass(new SocialsharesController());
        foreach ($a->getMethods() as $method) {
            if ($a->getName() == (string)$method->class) {
                $doc = new \DocBlockReader\Reader($method->class,$method->name);
                $x = $doc->getParameters();
                if(count($x) < 4){
                    continue;
                }
                $results[(string)$method->class][$method->name] = $doc->getParameters();
            }
        }

        $a = new \ReflectionClass(new ProductsController());
        foreach ($a->getMethods() as $method) {
            if ($a->getName() == (string)$method->class) {
                $doc = new \DocBlockReader\Reader($method->class,$method->name);
                $x = $doc->getParameters();
                if(count($x) < 4){
                    continue;
                }
                $results[(string)$method->class][$method->name] = $doc->getParameters();
            }
        }

        $a = new \ReflectionClass(new AnswersController());
        foreach ($a->getMethods() as $method) {
            if ($a->getName() == (string)$method->class) {
                $doc = new \DocBlockReader\Reader($method->class,$method->name);
                $x = $doc->getParameters();
                if(count($x) < 4){
                    continue;
                }
                $results[(string)$method->class][$method->name] = $doc->getParameters();
            }
        }
        
        $a = new \ReflectionClass(new TemplatemessagesController());
        foreach ($a->getMethods() as $method) {
            if ($a->getName() == (string)$method->class) {
                $doc = new \DocBlockReader\Reader($method->class,$method->name);
                $x = $doc->getParameters();
                if(count($x) < 4){
                    continue;
                }
                $results[(string)$method->class][$method->name] = $doc->getParameters();
            }
        }

        $this->set("results", $results);
    }

}
