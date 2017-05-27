<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Dashboard Controller
 *
 */
class DashboardController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
    }

    public function gearstats(){
        \Cake\Core\Configure::write('debug', true);
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
		
        exec("gearadmin --status",$out);
        $out = implode("\n",$out);
        echo $out;

        exit;
    }
    public function index(){
        
    }
}
