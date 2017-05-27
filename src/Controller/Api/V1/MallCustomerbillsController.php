<?php
namespace App\Controller\Api\V1;

use App\Controller\Api\V1\AppController;

/**
 * MallCustomerbills Controller
 *
 * @property \App\Model\Table\MallCustomerbillsTable $MallCustomerbills
 */
class MallCustomerbillsController extends AppController{
    
    /**
     * @apiDescription Save Customers bills
     * @apiUrl http://business.zakoopi.com/api/v1/mall-customerbills/submit-bill/(store_id).json
     * @apiRequestType POST method
     * @apiRequestData {bill_amount:"decimal",bill_photo: "<File>", customer_id:"int"}
     * @apiResponseType Based on _ext .xml for XML and .json for JSON
     * @apiResponseEx {"error": 0-1, "data":"<MallCustomerbillsEntity>"}
     */
    public function submitBill($store_id = null){
        $this->request->allowMethod(['post']);
        $d = $this->request->data;
        $result = json_encode($d);
        file_put_contents(WWW_ROOT.'rq.txt', print_r($d,true));
        
        $billTbl = \Cake\ORM\TableRegistry::get("MallCustomerbills");
        $path = "uploads" . DS . "bills" . DS . $store_id . "-" . $d['customer_id'] . "--" . $d['bill_photo']['name'];
        
        $result = [
            "error" => 1,
            "msg" => "Some error occured..."
        ];
        
        if(move_uploaded_file($d['bill_photo']['tmp_name'], WWW_ROOT . $path)){
            $ent = $billTbl->newEntity([
                "customer_id" => $d['customer_id'],
                "store_id" => $store_id,
                "bill_file" => $path,
                "amount" => $d['bill_amount'],
                "status" => "FOR_VERIFICATION"
            ]);
            if($billTbl->save($ent)){
                $result = [
                    "error" => 0,
                    "msg" => "Bill submitted successfuly..."
                ];
            }
        }
        
        $this->set('result', $result);
        $this->set('_serialize',['result']);
    }
    
    
}
