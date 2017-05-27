<?php
namespace App\Controller;

use App\Controller\AppController;


/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 */
class CustomersController extends AppController
{
    
    private $_colCountStore = 10;
    private $storeHeads = array(
        0 => 'id',
//        1 => 'store_id',
        1 => 'email',
        2 => 'name',
        3 => 'gender', 
        4 => 'contact_no',
        5 => 'dob',
        6 => 'doa',
        7 => 'age',
    );
    
    private $storeHeads2 = array(
        0 => 'id',
//        1 => 'store_id',
        1 => 'email',
        2 => 'name',
        3 => 'gender', 
        4 => 'contact_no',
        5 => 'dob',
        6 => 'doa',
        7 => 'age',
        8 => 'spouse_name',
        9 => 'spouse_date'
    );

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $limit = 10;
        if(@$this->request->query('limit')){
            $limit = $this->request->query('limit');
        }
        $this->paginate = [
            'contain' => ['Stores'],
            'limit' => $limit
        ];
        $customers = $this->Customers->find()->where([
            'Customers.store_id' => $this->_appSession->read('App.selectedStoreID'),
            'Customers.status' => 1,
            'Customers.soft_deleted' => 0
        ]);
        
        
        
        $count = $customers->count();
        $customers = $this->paginate($customers);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        
        $this->set(compact('customers','pager'));
        $this->set('_serialize', ['customers','pager']);
    }
    
    /**
     * Search method
     * 
     * @return \Cake\Network\Response|null
     */
    public function srchkey(){
        $d = $this->request->data;
        $limit = 10;
        if(@$this->request->query('limit')){
            $limit = $this->request->query('limit');
        }
        $this->paginate = [
            'limit' => $limit
        ];
        $customers = $this->Customers->find()->where([
            'Customers.store_id' => $this->_appSession->read('App.selectedStoreID'),
            'Customers.status' => 1,
            'Customers.soft_deleted' => 0,
            'OR' => [
                'Customers.name LIKE' => '%' . $d['keyword'] . '%',
                'Customers.contact_no LIKE' => '%' . $d['keyword'] . '%',
                'Customers.email LIKE' => '%' . $d['keyword'] . '%'
            ]
        ]);
        $count = $customers->count();
        $customers = $this->paginate($customers);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        
        $this->set(compact('customers','pager'));
        $this->set('_serialize', ['customers','pager']);
    }



    /**
     * View method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customer = $this->Customers->get($id, [
            'contain' => ['Stores', 'CustomerVisits', 'Messages', 'Purchases', 'SharedcodeRedeemed', 'Sharedcodes', 'Welcomemsgs']
        ]);

        $this->set('customer', $customer);
        $this->set('_serialize', ['customer']);
    }
    
    /**
     * Delete method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        
        $this->request->allowMethod(['post', 'delete']);
        if ($this->Customers->updateAll(['soft_deleted' => 1],['id' => $id])) {
            $result = [
                'msg' => "Your customer has been deleted.",
                'error' => 0
            ];
        } else {
            $result = [
                'msg' => "Your customer has been deleted.",
                'error' => 1
            ];
        }
        $this->set('result', $result);
        $this->set('_serialize',['result']);
    }
    
    
    public function addOrUpdate(){
        $this->request->allowMethod(['post']);
        $d = $this->request->data;
        unset($d['created']);
        unset($d['store']);
        
        if(!isset($d['id'])){
            $cst = $this->Customers->find()->where([
                "Customers.store_id" => $this->_appSession->read('App.selectedStoreID'),
                "Customers.contact_no" => $d['contact_no']
            ])->first();
            if(!$cst){
                $d['store_id'] = $this->_appSession->read('App.selectedStoreID');
                $d['opt_in'] = 1;
                $d['status'] = 1;
                $d['soft_deleted'] = 0;
                $d['added_by'] = 'DASHBOARD';
                $ent = $this->Customers->newEntity();
                $ent = $this->Customers->patchEntity($ent, $d);
                $r = $this->Customers->save($ent);
                if($r){
                    $result = [
                        'error' => 0,
                        'msg' => "Saved successfully...",
                        'd' => $ent
                    ];
                    $checkMobileNo = preg_match("^[789]\d{9}$^", $r->contact_no);
                    if ($checkMobileNo) {
                        $methods = new \App\Common\Methods();
                                
                        $templatemessageTbl = \Cake\ORM\TableRegistry::get('Templatemessages');
                        $templateMessage = $templatemessageTbl->find()->where(['type' => 'welcome-message','store_id' => $this->_appSession->read('App.selectedStoreID')])->first();
                        $newmessage = $templateMessage['message'];
//                        $msg = str_replace('{{cstName}}', (strlen($r->name) < 3 ? "customer" : $r->name), $newmessage) ." To unsubscribe SMS USUBZK". str_pad($this->_appSession->read('App.selectedStoreID'), 4, "0", STR_PAD_LEFT) ." to 9220092200";
                        $msg = str_replace('{{cstName}}', (strlen($r->name) < 3 ? "customer" : $r->name), $newmessage);                        
                        $this->loadModel('Clients');
                        $clntSndrId =$this->Clients->find()->select(['id','senderid'])->where(['id' => $this->_appSession->read('App.selectedClienteID')])->first();
                        $methods->smsgshup($r->contact_no, $msg, $this->_appSession->read('App.selectedClienteID'), $clntSndrId, "New Customer(FROM DASHBOARD) SMS", $r->id, $this->_appSession->read('App.selectedStoreID'));
                    }
                }else{
                    $result = [
                        'error' => 1,
                        'msg' => "Some error occured...",
                        'd' => $ent->errors()
                    ];
                }
            }else{
                $result = [
                    'error'=>1,
                    'msg' => 'contact number already exit'
                ];
            }
        }else{
            $ent = $this->Customers->get($d['id']);
            $ent = $this->Customers->patchEntity($ent, $d);
            $r = $this->Customers->save($ent);
            if($r){
                $result = [
                    'error' => 0,
                    'msg' => "Saved successfully...",
                    'd' => $ent
                ];
            }else{
                $result = [
                    'error' => 1,
                    'msg' => "Some error occured...",
                    'd' => $ent->errors()
                ];
            }
        }
        $this->set('result', $result);
        $this->set('_serialize',['result']);
    }
    
    
    /**
     * Export Customers List By NPS to EXCEL
     */
    public function exportToExcelByNps() {
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', -1);
        \Cake\Core\Configure::write('debug', FALSE);  
            $oCnd = [];
            $dataSet = [];
            $heads = [
                "ID","Customer Name","Country Code","Mobile No.","Came At","Promotor"
            ];
            
            $this->loadModel('Answers');
            $strId = $this->_appSession->read('App.selectedStoreID');
            $answers = $this->Answers->find()
                    ->hydrate(false)
                    ->matching('Questions', function($q) use($strId){return $q->where(['Questions.store_id' => $strId]);})
                    ->select(['id','customers_id','question_id','answer','created'])
                    ->contain([
                        'Questions'=>function($q){return $q->select(['id','store_id','question','view_type']);},
                        'Customers'=>function($q){return $q->select(['id','name','contact_no','country_code']);}
                    ])
                    ->order(['Answers.question_id' => 'asc'])
            ->all()->toArray();
            $ans = new \Cake\Collection\Collection($answers);
            $ans = $ans->groupBy("created")->toArray();        
            $queTbl = \Cake\ORM\TableRegistry::get('Questions');
            $queRecs = $queTbl->find()->where(['Questions.store_id'=>$strId])->toArray();
            foreach($queRecs as $qu){
                $heads[] = $qu['question'];
            }
            $dataSet[] = $heads;
            foreach ($ans as $ak => $av){
                $data = [];
                $data[0] = count($dataSet);
                $data[1] = $av[0]['customer']['name'];
                $data[2] = $av[0]['customer']['country_code'];
                $data[3] = $av[0]['customer']['contact_no'];
                $data[4] = date('c',$ak);
                foreach($av as $b){
                    if($b['question']['view_type'] == "nps"){
                        $value = explode('/', $b['answer'])[0];
                        if($value<5){
                            $pm = "Demoter";
                        }elseif($value >= 5 && $value <= 8){
                            $pm = "Passive";
                        }else{
                            $pm = "Promoter";
                        }
                        $data[5] = $pm;
                    }
                    $data[] = $b['answer'];
                }
                $dataSet[] = $data;
            }
            $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;
            \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, []);
            $excel2 = \PHPExcel_IOFactory::createReader('Excel2007');
            $excel2 = $excel2->load(WWW_ROOT . 'data' . DS . 'export.xlsx'); // Empty Sheet
            $objWorksheet = $excel2->setActiveSheetIndex(0);
            foreach ($dataSet as $ik => $i){
                foreach ($i as $jk => $j){
                    $objWorksheet->setCellValueByColumnAndRow($jk, $ik+1, $j);
                }
            }
            $objWriter = \PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
            ob_start();
            $objWriter->save('php://output');
            $this->response->body(ob_get_clean());
            $this->response->type('xlsx');
            $this->response->download('ZKP-'.date('d-m-Y').'.xlsx');
            return $this->response;
    }
    
    public function exportToExcel() {
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', -1);
        \Cake\Core\Configure::write('debug', FALSE); 
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
            $oCnd = [];
            $dataSet = [];
            $heads = [
                "ID","Customer Name","Country Code","Mobile No.","Came At","Email","Gender","DOB","DOA","Added Via","Subscribed","Active","Tags"
            ];
            
            $this->loadModel('Answers');
            $strId = $this->_appSession->read('App.selectedStoreID');
            $customers = $cstTbl->find()->where([
                "Customers.store_id" => $strId
            ])->orderAsc('name')->all();
            $dataSet[] = $heads;
            foreach ($customers as $av){
                $data = [];
                $data[0] = $av->id;
                $data[1] = $av->name;
                $data[2] = $av->country_code;
                $data[3] = $av->contact_no;
                $data[4] = date('c',$av->created);
                $data[5] = $av->email;
                $data[6] = $av->gender == 0 ? "Female" : ($av->gender == 1 ? "Male" : "Unspecified");
                $data[7] = $av->dob;
                $data[8] = $av->doa;
                $data[9] = $av->added_by;
                $data[10] = $av->opt_in;
                $data[11] = $av->is_sent;
                $data[12] = $av->tags;
                $dataSet[] = $data;
            }
            $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;
            \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, []);
            $excel2 = \PHPExcel_IOFactory::createReader('Excel2007');
            $excel2 = $excel2->load(WWW_ROOT . 'data' . DS . 'export.xlsx'); // Empty Sheet
            $objWorksheet = $excel2->setActiveSheetIndex(0);
            foreach ($dataSet as $ik => $i){
                foreach ($i as $jk => $j){
                    $objWorksheet->setCellValueByColumnAndRow($jk, $ik+1, $j);
                }
            }
            $objWriter = \PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
            ob_start();
            $objWriter->save('php://output');
            $this->response->body(ob_get_clean());
            $this->response->type('xlsx');
            $this->response->download('ZKP-'.date('d-m-Y').'.xlsx');
            return $this->response;
    }
    
    /**
     * Import Customers from csv file
     */
    public function uploadcsv() {
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '-1');
        $gmc= new \GearmanClient();
        $gmc->addServer();
        $gmc->setCompleteCallback([$this,"reverse_complete"]);
                
        $templatemessageTbl = \Cake\ORM\TableRegistry::get('Templatemessages');
        $templateMessage = $templatemessageTbl->find()->where(['type' => 'welcome-message','store_id' => $this->_appSession->read('App.selectedStoreID')])->first();
        $newmessage = $templateMessage['message'];
        
        $this->viewBuilder()->layout('ajax');
        if ($this->request->is('post')) {
            $ftype = pathinfo($this->request->data['csv']['name'], PATHINFO_EXTENSION);
            $handle = fopen($this->request->data['csv']['tmp_name'], "r");
            $d = $this->request->data['csv'];
            if ($d['error'] == 0) {
                $file = WWW_ROOT . 'tmp' . DS . time() . $d['name'];
                if (move_uploaded_file($d['tmp_name'], $file)) {
                    $_tstamp = time();
                    if ($ftype == 'csv') {
                        $reader = \PHPExcel_IOFactory::createReader('CSV')->setDelimiter(',')->setEnclosure('"')->setSheetIndex(0)->load($file);
                    } else {
                        $reader = \PHPExcel_IOFactory::load($file);
                    }
                    $objWorksheet = $reader->setActiveSheetIndex(0);
                    $highestRow = $objWorksheet->getHighestRow();
                    $highestColumn = $objWorksheet->getHighestColumn();
                    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
                    //read from file
                    $data = [];
                    $kidsData = [];
                    $kidsLastIndex = 1;
                    for ($row = 1; $row <= $highestRow; ++$row) {
                        if($row == 1){
                            continue;
                        }
                        if( $objWorksheet->getCellByColumnAndRow(1, $row)->getValue() == "" && $objWorksheet->getCellByColumnAndRow(4, $row)->getValue() == "" ){
                            continue;
                        }
                        $tmpData = [];
                        for ($col = 0; $col < $highestColumnIndex; ++$col) {
                            $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                            $value = trim($value);
                            if($col == 0){
                                $value = ""; //ignore id column => S.No
                            }
                            if($col > 9){
                               
                                if($kidsLastIndex % 2 == 0){
                                    $kidsData[count($kidsData) - 1]['kid_birth_date'] = $value;
                                }else{
                                    $kidsData[] = [
                                        "kid_name" => $value
                                    ];
                                }
                                $kidsLastIndex++;
                            }else{
                                if($this->storeHeads2[$col] == "gender"){
                                    if(strtoupper($value)=="MALE"){$value =1;}
                                    elseif(strtoupper($value)=="FEMALE"){$value = 0;}
                                    elseif(empty($value)){$value = 2;}
                                }
                                if($this->storeHeads2[$col] == 'age' && empty($value)){
                                    $value = 0;
                                }
                                $tmpData[$this->storeHeads2[$col]] = $value;
                            }

                        }
                        $tmpData['status'] = 1;
                        $tmpData['added_by'] = "CSV";
                        $tmpData['kids_info'] = json_encode(["result" => $kidsData]);
                        $data[] = $tmpData;
                    }
                    $users = $this->Customers->newEntities($data);
                    
                    /* Read Sender ID here for Gearman START */
                    $store = $this->Customers->Stores->find()->where([
                        'Stores.id' => $this->_appSession->read('App.selectedStoreID'),
                        'Stores.status' => 1
                    ])->contain([
                        'Brands' => function($q){
                            return $q->select(['id','client_id'])->contain([
                                'Clients' => function($q){
                                    return $q->select(['id','senderid']);
                                }
                            ]);
                        }
                    ])->select([
                        'Stores.id','Stores.brand_id'
                    ]);
                    $clntId = $store->brand->client->id;
                    $clntSndrId = $store->brand->client->senderid;
                    
                    /* Read Sender ID here for Gearman ENDS */
                    
                    foreach($users as $user){
                        $userId = '';
                        $checkUser = '';
                        $checkUser = $this->Customers->find()->where([
                            'store_id' => $this->_appSession->read('App.selectedStoreID'),
//                            'OR' => [
                                'contact_no' => $user['contact_no'],
//                                'email' => $user['email'],
//                            ]
                        ])->first();
                        $user->store_id = $this->_appSession->read('App.selectedStoreID');
                        if($checkUser){
                            $user->id = $checkUser->id;
//                            debug($user); exit;
                        }
                        $res[] = [ "r" =>  $sResult =  $this->Customers->save($user), "e" => $eResult = $user->errors()];
                        $result = 1;
                        if(empty($eResult)){
//                            $msg = str_replace('{{cstName}}', (strlen($user['name']) < 3 ? "customer" : $user['name']), $newmessage) ." To unsubscribe SMS USUBZK". str_pad($this->_appSession->read('App.selectedStoreID'), 4, "0", STR_PAD_LEFT) ." to 9220092200";
                            $msg = str_replace('{{cstName}}', (strlen($user['name']) < 3 ? "customer" : $user['name']), $newmessage);
                            $dt = [
                                    "message"=>$msg,
                                    'store_id'=>$this->_appSession->read('App.selectedStoreID'),
                                    'client_id'=>  $this->_appSession->read('App.selectedClientID'),
                                    'contact_no' => $user['contact_no'],
                                    'sender_id' => $clntSndrId,
                                    'customer_id' => $sResult->id
                                ];
                            $task = $gmc->addTaskBackground("cstcsvmsg", serialize($dt),null);
                        }
                    }
                    $gmc->runTasks();
                }
            }
        }
//        return $this->redirect('/customers');
        $this->set('result',$result);
        $this->set('_serialize', ['result']);
    }
    /**
     * 
     * @return type
     */
    public function uploadcsv2() {
        $this->viewBuilder()->layout('ajax');
        $user = $this->Customers->newEntity();
        if ($this->request->is('post')) {
            $ftype = pathinfo($this->request->data['csv']['name'], PATHINFO_EXTENSION);
            $handle = fopen($this->request->data['csv']['tmp_name'], "r");
            ini_set('max_execution_time', -1);
            ini_set('memory_limit', '512M');
            $d = $this->request->data['csv'];
            if ($d['error'] == 0) {
                $file = WWW_ROOT . 'tmp' . DS . time() . $d['name'];
                if (move_uploaded_file($d['tmp_name'], $file)) {
                    $_tstamp = time();
                    if ($ftype == 'csv') {
                        $reader = \PHPExcel_IOFactory::createReader('CSV')->setDelimiter(',')->setEnclosure('"')->setSheetIndex(0)->load($file);
                    } else {
                        $reader = \PHPExcel_IOFactory::load($file);
                    }
                    $objWorksheet = $reader->setActiveSheetIndex(0);
                    $highestRow = $objWorksheet->getHighestRow();
                    $highestColumn = $objWorksheet->getHighestColumn();
                    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
                    //read from file
                    $data = [];
                    for ($row = 1; $row <= $highestRow; ++$row) {
                        if($row == 1){
                            continue;
                        }
                        if( $objWorksheet->getCellByColumnAndRow(1, $row)->getValue() == "" && $objWorksheet->getCellByColumnAndRow(4, $row)->getValue() == "" ){
                            continue;
                        }
                        $tmpData = [];
                        for ($col = 0; $col < $highestColumnIndex; ++$col) {
                            $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                            $value = trim($value);
                            if($col == 0){
                                $value = ""; //ignore id column => S.No
                            }
                            if($this->storeHeads2[$col] == 'gender' && empty($value)){
                                $value = 2;
                            }
                            if($this->storeHeads2[$col] == 'age' && empty($value)){
                                $value = 0;
                            }
                            $tmpData[$this->storeHeads2[$col]] = $value;
                        }
                        $tmpData['status'] = 1;
                        $tmpData['added_by'] = "CSV";
                        $data[] = $tmpData;
                    }
//                    debug($data);exit;
                    $users = $this->Customers->newEntities($data);
//                    debug($users);
                    foreach($users as $user){
                        $userId = '';
                        $checkUser = '';
                        $checkUser = $this->Customers->find()->where([
                            'store_id' => $this->_appSession->read('App.selectedStoreID'),
//                            'OR' => [
                                'contact_no' => $user['contact_no'],
//                                'email' => $user['email'],
//                            ]
                        ])->first();
                        $user->store_id = $this->_appSession->read('App.selectedStoreID');
                        if($checkUser){
                            $user->id = $checkUser->id;
//                            debug($user); exit;
                        }
                        $res = $this->Customers->save($user);
                        $result = 1;
                    }
                }
            }
        }
        return $this->redirect('/customers');
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }
    
    
    public function frt(){
        $cst = \Cake\ORM\TableRegistry::get('Customers');
        $list = $cst->find()->select([
            "contact_no","name","id"
        ])->where([
            "Customers.contact_no <>" => ""
        ])->group('contact_no');
        $this->set("list",$list);
        $this->set('_serialize', ['list']);
    }
    
    public function t(){
        $qry = $this->Customers->find()->where(['status' => 1]);

        $limit =  $this->request->query['limit'];
        $count = $qry->count();
        $customers = $this->paginate($qry);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        
        $this->set(compact('customers','pager'));
        $this->set('_serialize', ['customers','pager']);
    }
    
    public function measurement(){
        $result = [
            'error' => 1,
            'msg' => 'something went wrong please try again',
            'results' => ''
        ];
        $measurements = $this->Customers->Customermeasurements->find()->select(['measurement_type'])->group('measurement_type')->toArray();
        if($measurements){
            $result = [
                'error' => 0,
                'results' => $measurements
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
    }
    
    public function customerMeasurement(){
        \Cake\Core\Configure::write('debug',FALSE);
        $d = $this->request->data;
//        $d['data']['select'] = "sherwani";
//        $d['data']['type'] = "11";
        $result = [
            'error' => 1,
            'msg' => 'No record found',
            'measurement_type' => $d['data']['type'],
            'results' => ''
        ];
        if($d['data']['select'] && empty($d['data']['type'])){
            $customermeasurements = $this->Customers->Customermeasurements->find()->where([
                'measurement_type' => $d['data']['select'],
                'Customer_id' => $d['cid']
            ])->select([
                'customer_id','measurement_type','measurement_name','value'
            ])->toArray();
            if($customermeasurements){
                foreach($customermeasurements as $customermeasurement){
                    $customermeasurement['measurement_type'] = $d['data']['select'];
                    $res[] = $customermeasurement;
                }
                $result = [
                    'error' => 0,
                    'msg' => 'success',
                    'measurement_type' => $d['data']['select'],
                    'results' => $res
                ];
            }else{
                $result = [
                    'error' => 1,
                    'msg' => 'No record found',
                    'measurement_type' => $d['data']['select'],
                    'results' => ""
                ];
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
    }
    
    public function addNewMeasurement(){
        $this->request->allowMethod(['post','put']);
        $d = $this->request->data['data'];
        $result = [
            'error' => 0,
            'msg' => 'Save successfully'
        ];
        $this->Customers->Customermeasurements->deleteAll([
            'customer_id' => $d[0]['customer_id'],
            'measurement_type' => $d[0]['measurement_type']
        ]);
        $d1 = $this->Customers->Customermeasurements->newEntities($d);
        foreach ($d1 as $d2){
            if($d2->value  && $d2->measurement_name && $d2->measurement_name && $d2->customer_id){
                $this->Customers->Customermeasurements->save($d2);
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
    }
    public function storeVistis(){
        $d = $this->request->data;
        $totalVisit = $this->Customers->CustomerVisits->find()->where([
            'customer_id' => $d['customer_id'],
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
        ])->count();
        if($totalVisit){
            $result = [
                'error' => 0,
                'msg' => 'success',
                'count' => $totalVisit
            ];
        }else{
            $result = [
                'error' => 1,
                'msg' => 'this customer never visit on store',
                'count' => 0
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize',['result']);
    }
    
    /**
     * 
     * @param type $access_token Google AccessToken
     * @param type $number Mobile Number to Save on Google 
     * @param type $email
     * @param type $name
     * @return type XMLString
     */
    private function createContact($access_token,$number,$email='',$name='') {
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $client = new \Cake\Network\Http\Client([
            'ssl_verify_peer' => false
        ]);
        $xmlString = "<atom:entry xmlns:atom='http://www.w3.org/2005/Atom' xmlns:gd='http://schemas.google.com/g/2005'>
                            <atom:category scheme='http://schemas.google.com/g/2005#kind'
                                           term='http://schemas.google.com/contact/2008#contact'/>
                           <atom:title>$name</atom:title>
                            <gd:phoneNumber rel='http://schemas.google.com/g/2005#work'
                                            primary='true'>
                                $number
                            </gd:phoneNumber>
                            <gd:email rel='http://schemas.google.com/g/2005#work'
                            primary='true'
                            address='$email' displayName='$name'/>
                         </atom:entry>";

        //pr($access_token); exit;
        $response = $client->post(
                'https://www.google.com/m8/feeds/contacts/default/full?access_token=' . $access_token['access_token'], (string) $xmlString, ['headers' => ['Content-Type' => 'application/atom+xml']]
        );

        return $response->body();
    }

    public function googleContactApi($val = null) {
        $session = $this->request->session();
        \Cake\Core\Configure::write('debug', true);
        $client = new \Google_Client();
        $client->setApplicationName('BusinessZKP');
        $client->setClientId("64838761597-ni4qles5it3rdr4hs804o84lae2c3cfh.apps.googleusercontent.com");
        $client->setClientSecret("SGW53uaOVAhl0B1V0RreCoPY");
        $client->setRedirectUri(\Cake\Routing\Router::url('/customers/google-contact-api/authdone', true));
        $client->addScope([
            \Google_Service_Plus::PLUS_LOGIN,
            \Google_Service_People::CONTACTS,
            \Google_Service_People::CONTACTS_READONLY
        ]);
        $client->setAccessType('offline');

        if ($session->check('GAccessToken')) {
            $client->setAccessToken($session->read('GAccessToken'));
            $contaceService = new \Google_Service_People($client);
//            $contactList = $contaceService->people_connections->listPeopleConnections('people/me', array('pageSize' => 100))->getConnections();
            //pr(get_class_methods($contactList));
            $customers = $this->Customers->find()->select([
                'name','email','contact_no'
            ])->where([
                'store_id' => $this->_appSession->read('App.selectedStoreID'),
                'contact_no <>' => ''
            ])->toArray();
            foreach ($customers as $customer){
                $createdContact = simplexml_load_string($this->createContact(
                        $session->read('GAccessToken'),
                        $customer->contact_no,
                        $customer->email,
                        $customer->name
                ));
            }
            $this->redirect('/#/app/customers/list');
        } else {
            if ($val == null) {
                $client->setRedirectUri(\Cake\Routing\Router::url('/test/googleContactApi/authdone', true));
                return $this->redirect($client->createAuthUrl());
            } else {
                $code = $this->request->query('code');
                $client->authenticate($code);
                $accessToken = $client->getAccessToken();  // save in DB
                $session->write('GAccessToken', $accessToken);
                return $this->redirect('/test/googleContactApi');
            }
        }
    }
    
    
    function reverse_created($task)
    {
        echo "CREATED: " . $task->jobHandle() . "\n";
    }

    function reverse_status($task)
    {
        echo "STATUS: " . $task->jobHandle() . " - " . $task->taskNumerator() . 
             "/" . $task->taskDenominator() . "\n";
    }

    function reverse_complete($task)
    {
        echo "C: " . $task->jobHandle() . ", " . $task->data() . "\n";
    }

    function reverse_fail($task)
    {
        echo "FAILED: " . $task->jobHandle() . "\n";
    }

    function reverse_data($task)
    {
        echo "DATA: " . $task->data() . "\n";
    }
}
