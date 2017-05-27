<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * Templatemessages Controller
 *
 * @property \App\Model\Table\TemplatemessagesTable $Templatemessages
 */
class TemplatemessagesController extends AppController
{

    /**
     * @apiDescription welcome Screen Left side text
     * @apiUrl http://www.business.zakoopi.com/api/templatemessages/welcome-screen-text/<store_id>.json
     * @apiRequestType GET/POST method
     * @apiRequestData 
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"data":{"code": "0/1","msg":"msg"}}
     */
    public function welcomeScreenText($store_id){
        $tbl = \Cake\ORM\TableRegistry::get('Templatemessages');
        $ent = $tbl->find()->where([
            "store_id" => $store_id,
            "type" => "welcome-message-screen-text"
        ])->first();
        $msg = "Sign Up for exciting offers!";
        if($ent){
            $msg = $ent->message;
        }
        $result = [
            "code" => 0,
            "msg"  => strtoupper($msg)
        ];
        $this->set("data",$result);
        $this->set('_serialize', ['data']);
    }
    
}
