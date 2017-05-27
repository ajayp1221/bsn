<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Cron Controller
 *
 * @property \App\Model\Table\MessagesTable $Messages
 * @property \App\Model\Table\CampaignsTable $Campaigns
 * @property \App\Model\Table\ClientsTable $Clients
 * @property \App\Model\Table\CustomersTable $CustomersAlbumshares
 * @property \App\Model\Table\AlbumsharesTable $Albumshares
 * @property \App\Model\Table\StoresTable $Stores
 */

class RealtimeController extends AppController{
    
    public function beforeFilter(\Cake\Event\Event $event) {
         parent::beforeFilter($event);
         $this->Auth->allow();
    }
    
    public function gupshup(){
        $d = $this->request->query;
        $gshupTbl = \Cake\ORM\TableRegistry::get('Gshuplogs');
        $msgTbl = \Cake\ORM\TableRegistry::get('Messages');
        sleep(15);
        $gEnt = $gshupTbl->find()->where([
           "external_id" => $d['externalId']
        ])->first();
        if($gEnt){
            $gEnt->set('status', $d['cause']);
            $gEnt->set("modified", time());
            $gshupTbl->save($gEnt);
        }
        $msgEnt = $msgTbl->find()->where([
            "Messages.external_msgid" => $d['externalId']
        ])->first();
        //file_put_contents("rq.txt", print_r($msgEnt,true));
        if($msgEnt){
            $msgEnt->cause = $d['cause'];
            $msgEnt->status =  $d['status'];
            $msgTbl->save($msgEnt);
        }
        exit;
    }

}