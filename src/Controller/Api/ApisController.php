<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Routing\Router;

/**
 * Apis Controller
 */
class ApisController extends AppController
{

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
        $this->viewBuilder()->layout('ajax');
    }
    
    public function index(){
        $indexInfo['description'] = "App Client Login ";
        $indexInfo['url'] = Router::url('/', true)."api/common/check-client.json";
        $indexInfo['parameters'] = 
        '<b>[username] - </b>Client Email<br>
        <b>[password] - </b>Password<br>';
        $indexarr[] = $indexInfo;
        
        $indexInfo['description'] = "App Client Logout";
        $indexInfo['url'] = Router::url('/', true)."api/common/logout";
        $indexInfo['parameters'] = '';
        $indexarr[] = $indexInfo;
        
        $indexInfo['description'] = "App Client Stores";
        $indexInfo['url'] = Router::url('/', true)."api/common/first-start.json";
        $indexInfo['parameters'] = '';
        $indexarr[] = $indexInfo;
        
        $indexInfo['description'] = "Check Customer";
        $indexInfo['url'] = Router::url('/', TRUE)."api/common/check-customer.json";
        $indexInfo['parameters'] = '<b>[contact_no] - </b>Customer Mobile Number<br>';
        $indexarr[] = $indexInfo;
        
        $indexInfo['description'] = "Add New Or Update Customer";
        $indexInfo['url'] = Router::url('/',TRUE)."api/common/add-update-customer.json";
        $indexInfo['parameters'] = 
        '<b>[name] - </b>Customer Name<br>
        <b>[contact_no] - </b>Customer Contact Number<br>
        <b>[email] - </b>Customer Email<br>
        <b>[store_id] - </b>Store Id<br>
        <b>[gender] - </b>Gender 1(Male) / 0(Female)<br>
        <b>[dob] - </b>Date Of Birth<br>
        <b>[doa] - </b>Date Of Anniversary<br>
        <b>[age] - </b>Age Of Customer<br>
        <b>[id] - </b>Id(If user need to update)';
        $indexarr[] = $indexInfo;

        $indexInfo['description'] = "Add New Or Update Customer Specific Stores(Studio De Royale)";
        $indexInfo['url'] = Router::url('/',TRUE)."api/common/add-update-customer2.json";
        $indexInfo['parameters'] = 
        '<b>[name] - </b>Customer Name<br>
        <b>[email] - </b>Customer Email<br>
        <b>[contact_no] - </b>Customer Contact Number<br>
        <b>[store_id] - </b>Store Id<br>
        <b>[gender] - </b>Gender 1(Male) / 0(Female)<br>
        <b>[dob] - </b>Date Of Birth<br>
        <b>[doa] - </b>Date Of Anniversary<br>
        <b>[age] - </b>Age Of Customer<br>
        <b>[id] - </b>Id(If user need to update)';
        $indexarr[] = $indexInfo;
        
        $indexInfo['description'] = "Recommended Friend";
        $indexInfo['url'] = Router::url('/')."api/recommend-screen/recommended";
        $indexInfo['parameters'] = 
        '<b>[store_id] - </b>Store Name<br>
        <b>[customer_id] - </b>Customer Id<br>
        <b>[recommended] - </b>E.G - {"final":[{"number":"7838283001","name":"karan"},{"number":"9646631704","name":"dharam"}]}<br>';
        $indexarr[] = $indexInfo;
        
        $this->set('IndexDetail',$indexarr);
    }
}
