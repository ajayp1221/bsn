<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Api\V1;

use App\Controller\Api\V1\CommonController;
use App\Controller\Api\V1\RecommendScreenController;
use App\Controller\Api\V1\CustomerVisitsController;
use App\Controller\Api\V1\NotificationsController;
use App\Controller\Api\V1\SocialsharesController;
use App\Controller\Api\V1\ProductcatsController;
use App\Controller\Api\V1\AnswersController;
use App\Controller\Api\V1\TemplatemessagesController;
use App\Controller\Api\V1\StoreSettingsController;
use App\Controller\Api\V1\MallCustomerbillsController;


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
        
        $a = new \ReflectionClass(new StoreSettingsController());
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
        
        $a = new \ReflectionClass(new MallCustomerbillsController());
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
