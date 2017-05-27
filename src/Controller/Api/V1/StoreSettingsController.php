<?php
namespace App\Controller\Api\V1;

use App\Controller\Api\V1\AppController;

/**
 * StoreSettings Controller
 *
 * @property \App\Model\Table\StoreSettingsTable $StoreSettings
 */
class StoreSettingsController extends AppController{
    
    /**
     * @apiDescription Get all the Settings for Store
     * @apiUrl http://www.business.zakoopi.comapi/v1/store-settings/index/(store_id).json
     * @apiRequestType GET method
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"error": 0-1, "data":["<StoreSettingsEntity>","<StoreSettingsEntity>"]}
     */
    public function index($id = null){
        $tbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $data = $tbl->find()->where([
            "store_id" => $id
        ])->all();
        $result = [
            "error" => 0,
            "data" => $data
        ];
        $this->set("result",$result);
        $this->set("_serialize",['result']);
    }
    
    /**
     * @apiDescription Get Store Settings by Providing Meta Key
     * @apiUrl http://business.zakoopi.com/api/v1/store-settings/get-key/(store_id).json
     * @apiRequestType POST method
     * @apiRequestData {key:"<meta_key>"}
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"error": 0-1, "data":"<StoreSettingsEntity>"}
     */
    public function getKey($id = null){
        $this->request->allowMethod(['post']);
        $d = $this->request->data;
        $tbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $data = $tbl->find()->where([
            "store_id" => $id,
            "meta_key" => $d['key']
        ])->first();
        $result = [
                "error" => 1,
                "msg" => "Key Not Found for Store ID: " . $id,
                "data" => null
            ];
        if($data){
            $result = [
                "error" => 0,
                "msg" => "Response Okay",
                "data" => $data
            ];
        }
        $this->set("result",$result);
        $this->set("_serialize",['result']);
    }
}