<?php
namespace App\Controller;

use App\Controller\AppController;
/**
 * 
 * 
 */
class LoyaltyController extends AppController{
    
    /**
     * 
     */
    public function index(){
        
        $store_id = $this->_appSession->read('App.selectedStoreID');
        $strSetTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $lsettings = $strSetTbl->find()->where([
            'StoreSettings.store_id' => $store_id,
            'StoreSettings.meta_key LIKE' => 'loyalty%'
        ])->toArray();
        $settings = new \Cake\Collection\Collection($lsettings);
        $settings = $settings->groupBy('meta_key');
        $lmemTbl = \Cake\ORM\TableRegistry::get('Loyaltymembers');
        if($this->request->query('s')){
            $members = $lmemTbl->find()->contain(['Customers'=>function($q){
                return $q->select(['name','contact_no','id']);
            }])->where([
                'Loyaltymembers.store_id' => $this->_appSession->read('App.selectedStoreID'),
                'OR' => [ 'Customers.contact_no LIKE' => '%'.$this->request->query('s').'%', 'Customers.name LIKE' => '%'.$this->request->query('s').'%']
            ]);
        }else{
            $members = $lmemTbl->find()->contain(['Customers'=>function($q){
                return $q->select(['name','contact_no','id']);
            }])->where([
                'Loyaltymembers.store_id' => $this->_appSession->read('App.selectedStoreID')
            ]);
        }
        
        
        $strTbl = \Cake\ORM\TableRegistry::get('Stores');
        $store = $strTbl->get($store_id);
        $strName = $store->name;
        $lsettings = $settings->toArray();
        $members = $this->paginate($members);

        $limit = 20;
        $count = $members->count();
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        $this->set(compact('strName', 'lsettings','members','pager'));
        $this->set('_serialize',['strName','lsettings','members','pager']);
    }
    
    /**
     * 
     * @return type
     */
    public function addPoints(){
        $resultOut = [
            'error' => 1,
            'msg' => 'Unknown issue'
        ];
        $store_id = $this->_appSession->read('App.selectedStoreID');
        $d = $this->request->data;
        if($d['contact_one'] == ''){
            $resultOut['msg'] = 'Please provide contact no...';
            $this->set('result', $resultOut);
            $this->set('_serialize',['result']);
            return $this->render();
        }
        if($d['contact_one'] != $d['contact_two']){
            $resultOut['msg'] = 'Both the numbers aren\'t matching...';
            $this->set('result', $resultOut);
            $this->set('_serialize',['result']);
            return $this->render();
        }
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
        $lmemTbl = \Cake\ORM\TableRegistry::get('Loyaltymembers');
        $customer = $cstTbl->find()->where([
            'Customers.store_id' => $store_id,
            'Customers.contact_no' => $d['contact_one']
        ])->first();
        
        if(!$customer){
            $ent = $cstTbl->newEntity([
                'contact_no' => $d['contact_one'],
                'store_id' => $store_id,
                'added_by' => 'LOYALTY-PNL',
                'opt_in' => 1,
                'status' => 1,
                'soft_deleted' => 0,
                'is_sent' => 1
            ]);
            $customer = $cstTbl->save($ent);
        }
        $lmember = $lmemTbl->find()->where([
            'Loyaltymembers.customer_id' => $customer->id
        ])->first();
        if(!$lmember){
            $total_points = 0;
            $Cr = $d['points'];
            $pBL = 0;
            $Dr = 0;
            $ent = $lmemTbl->newEntity([
               'customer_id' =>  $customer->id,
               'store_id' => $store_id,
               'total_points' => $d['points'],
               'points_left' => $Cr,
               'points_used' => $Dr,
               'ratio' => $d['conversionRatio'],
               'created' => time(),
               'status' => 1,
            ]);
            $lmember = $lmemTbl->save($ent);
        }else{
            
            $Cr = $d['points'];
            $pBL = $lmember->points_left;
            $Dr = 0;
            
            $total_points = $lmember->total_points;
            $lmember->set('total_points', $total_points + $d['points']);
            $lmember->set('points_left', $pBL + $d['points']);
            $lmemTbl->save($lmember);
        }
        
        $lLedgerTbl = \Cake\ORM\TableRegistry::get('Loyaltyledger');
        $ent = $lLedgerTbl->newEntity([
            'loyaltymember_id' => $lmember->id,
            'points_credit' => $Cr,
            'points_debit' => 0,
            'points_balance' => $pBL + $Cr - $Dr,
            'comments' => $d['comments'],
            'dated' => time(),
            'ratio' => $d['conversionRatio']
        ]);
        if($lLedgerTbl->save($ent)){
            $store_id = $this->_appSession->read('App.selectedStoreID');
            $strTbl = \Cake\ORM\TableRegistry::get('Stores');
            $store = $strTbl->get($store_id, [
                'contain' => [
                    'Brands',
                    'Brands.Clients'
                ]
            ]);
            $store->brand->client->id;
            $customer->contact_no;
            
            $d['comments'] = str_replace('<<Pts>>', $Cr, $d['comments']);
            $d['comments'] = str_replace('<<Date>>', date('d-m-Y',time()), $d['comments']);
            $d['comments'] = str_replace('<<Bal>>', $pBL + $Cr - $Dr, $d['comments']);
            
            $methods = new \App\Common\Methods();
            if($num = $methods->checkNum($customer->contact_no)){
                $methods->smsgshup($num, $d['comments'], $store->brand->client->id, $store->brand->client->senderid,"Loyalty Points Added Alert",0,$customer->id,$store->id);
            }
            $resultOut['msg'] = $Cr." points successfully added!";
            $resultOut['error'] = 0;
        }
        $this->set('result', $resultOut);
        $this->set('_serialize',['result']);
    }
    
    
    /**
     * 
     * @return type
     */
    public function redeemPoints(){
        $resultOut = [
            'error' => 1,
            'msg' => 'Unknown issue'
        ];
        $store_id = $this->_appSession->read('App.selectedStoreID');
        $d = $this->request->data;
        if($d['contact_one'] == ''){
            $resultOut['msg'] =  'Please provide contact no...';
            $this->set('result', $resultOut);
            $this->set('_serialize',['result']);
            return $this->render();
        }
        if($d['contact_one'] != $d['contact_two']){
            $resultOut['msg'] =  'Both the numbers aren\'t matching...';
            $this->set('result', $resultOut);
            $this->set('_serialize',['result']);
            return $this->render();
        }
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
        $lmemTbl = \Cake\ORM\TableRegistry::get('Loyaltymembers');
        $customer = $cstTbl->find()->where([
            'Customers.store_id' => $store_id,
            'Customers.contact_no' => $d['contact_one']
        ])->first();
        
        if(!$customer){
            $this->Flash->error("Sorry! but your number is not registered in our regular listings...");
            return $this->redirect('/loyalty');
        }
        $lmember = $lmemTbl->find()->where([
            'Loyaltymembers.customer_id' => $customer->id
        ])->first();
        if(!$lmember){
            $this->Flash->error("Sorry! but your number is not registered for loyalty program...");
            return $this->redirect('/loyalty');
        }else{
            
            $Cr = 0;
            $pBL = $lmember->points_left;
            $Dr = $d['points'];
            if(($pBL + $Cr - $Dr) < 0){
                $this->Flash->error("Sorry! not enough balance in your account...");
                return $this->redirect('/loyalty');
            }
            $total_points = $lmember->total_points;
            $lmember->set('points_used', $lmember->points_used + $Dr);
            $lmember->set('points_left', $pBL - $Dr);
            $lmemTbl->save($lmember);
        }
        
        $lLedgerTbl = \Cake\ORM\TableRegistry::get('Loyaltyledger');
        $ent = $lLedgerTbl->newEntity([
            'loyaltymember_id' => $lmember->id,
            'points_credit' => $Cr,
            'points_debit' => $Dr,
            'points_balance' => $pBL + $Cr - $Dr,
            'comments' => $d['comments'],
            'dated' => time(),
            'ratio' => $d['conversionRatio']
        ]);
        if($lLedgerTbl->save($ent)){
            $store_id = $this->_appSession->read('App.selectedStoreID');
            $strTbl = \Cake\ORM\TableRegistry::get('Stores');
            $store = $strTbl->get($store_id, [
                'contain' => [
                    'Brands',
                    'Brands.Clients'
                ]
            ]);
            $store->brand->client->id;
            $customer->contact_no;
            $d['comments'] = str_replace('<<Pts>>', $Dr, $d['comments']);
            $d['comments'] = str_replace('<<Date>>', date('d-m-Y',time()), $d['comments']);
            $d['comments'] = str_replace('<<Bal>>', $pBL + $Cr - $Dr, $d['comments']);
            $methods = new \App\Common\Methods();
            if($num = $methods->checkNum($customer->contact_no)){
                $methods->smsgshup($num, $d['comments'] , $store->brand->client->id, $store->brand->client->senderid,"Loyalty Points Redeemed Alert",$customer->id,$store->id);
            }
            $resultOut['msg'] = $Dr." points successfully redeemed! Give ".$d['purchase_total']. " Rs off to the customer.";
            $resultOut['error'] = 0;
        }
        $this->set('result', $resultOut);
        $this->set('_serialize',['result']);
    }
    
    
    public function ledger($id=null){
        if($id == null){
            $result = [
                'error' => 1,
                'msg' => 'Select customer from loyalty'
            ];
        }else{
            $id = base64_decode(base64_decode(base64_decode($id)));
            $lLedgerTbl = \Cake\ORM\TableRegistry::get('Loyaltyledger');
            $data = $lLedgerTbl
                    ->find()
                    ->contain(['Loyaltymembers' => function($q){
                        return $q->select(['id','customer_id'])->contain(['Customers' => function($q){return $q->select(['id','name','contact_no']);}]);
                    }])
            ->where([
                'Loyaltyledger.loyaltymember_id' => $id
            ])->orderDesc('Loyaltyledger.id');
            if($data){
                $result = [
                    'error' => 0,
                    'msg' => 'success',
                    'data' => $data
                ];
            }else{
                $result = [
                    'error' => 1,
                    'msg' => 'No record found'
                ];
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize',['result']);
    }

    public function loyaltySettings(){
        $store_id = $this->_appSession->read('App.selectedStoreID');
        $d = $this->request->data;
        //pr($d); exit;
        $strSetTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $ent = $strSetTbl->find()->where([
            'StoreSettings.store_id' => $store_id,
            'StoreSettings.meta_key' => 'loyalty-conversion-ratio-cr'
        ])->first();
        if(!$ent){
            $ent = $strSetTbl->newEntity([
                'store_id' => $store_id,
                'meta_key' => 'loyalty-conversion-ratio-cr',
                'meta_value' => $d['loyalty-conversion-ratio-cr']
            ]);
        }else {
            $ent->set('meta_value', $d['loyalty-conversion-ratio-cr']);
        }
        $strSetTbl->save($ent);
        
        $ent = $strSetTbl->find()->where([
            'StoreSettings.store_id' => $store_id,
            'StoreSettings.meta_key' => 'loyalty-conversion-ratio-dr'
        ])->first();
        if(!$ent){
            $ent = $strSetTbl->newEntity([
                'store_id' => $store_id,
                'meta_key' => 'loyalty-conversion-ratio-dr',
                'meta_value' => $d['loyalty-conversion-ratio-dr']
            ]);
        }else {
            $ent->set('meta_value', $d['loyalty-conversion-ratio-dr']);
        }
        $strSetTbl->save($ent);
        
        $ent = $strSetTbl->find()->where([
            'StoreSettings.store_id' => $store_id,
            'StoreSettings.meta_key' => 'loyalty-min-balance-4-redemption'
        ])->first();
        if(!$ent){
            $ent = $strSetTbl->newEntity([
                'store_id' => $store_id,
                'meta_key' => 'loyalty-min-balance-4-redemption',
                'meta_value' => $d['loyalty-min-balance-4-redemption']
            ]);
        }else {
            $ent->set('meta_value', $d['loyalty-min-balance-4-redemption']);
        }
        $strSetTbl->save($ent);
        
        $ent = $strSetTbl->find()->where([
            'StoreSettings.store_id' => $store_id,
            'StoreSettings.meta_key' => 'loyalty-max-award-a-day'
        ])->first();
        if(!$ent){
            $ent = $strSetTbl->newEntity([
                'store_id' => $store_id,
                'meta_key' => 'loyalty-max-award-a-day',
                'meta_value' => $d['loyalty-max-award-a-day']
            ]);
        }else {
            $ent->set('meta_value', $d['loyalty-max-award-a-day']);
        }
        if($strSetTbl->save($ent)){
            $result = [
                'error' => 0,
                'msg' => 'Settings updated for loyalty program'
            ];
        }else{
            $result = [
                'error' => 1,
                'msg' => 'something went wrong please try again'
            ];
        }
//        pr($result); exit;
        $this->set(compact('result'));
        $this->set('_serialize',['result']);
        return $this->redirect('/#/loyalty/index');
    }
    
    public function loyaltyMsg($type = null){
        $this->request->allowMethod(['post','put']);
        $storeSettingsTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $d = $this->request->data;
        $store_id = $this->_appSession->read('App.selectedStoreID');
        if($type == "cr"){
            $meta_key = "loyalty-message-cr";
        }
        if($type == "dr"){
            $meta_key = "loyalty-message-dr";
        }
        $ent = $storeSettingsTbl->find()->where([
            "StoreSettings.store_id" => $store_id,
            "StoreSettings.meta_key" => $meta_key
        ])->first();
        if($ent){
            $ent->meta_value = $d['msg'];
        }else{
            $ent = $storeSettingsTbl->newEntity([
                'store_id' => $store_id,
                'meta_key' => $meta_key,
                'meta_value' => $d['msg']
            ]);
        }
        $result = [];
        if($storeSettingsTbl->save($ent)){
            $result = [
              'error' => 0,
              'msg' => 'Information updated successfully'
            ];
        }else{
            $result = [
              'error' => 1,
              'msg' => 'Unable to save'
            ];
        }
        $this->set('result',$result);
        $this->set('_serialize',['result']);
    }




    public function update(){
        $d = $this->request->data;
        unset($d['loyaltymember']);
        $tbl = \Cake\ORM\TableRegistry::get('Loyaltyledger');
        $lmemTbl = \Cake\ORM\TableRegistry::get('Loyaltymembers');
        $entity = $tbl->get($d['id']);
        $lmember = $lmemTbl->find()->where([
            'id' => $entity->loyaltymember_id
        ])->first();
        if($entity->points_credit == "0"){
            $oldVal = $entity->points_debit;
            $newVal = (float)$d['points_debit'];
            $type = 'debit';
        }
        if($entity->points_debit == "0"){
            $oldVal = $entity->points_credit;
            $newVal = (float)$d['points_credit'];
            $type = 'credit';
        }
        if($newVal < 0 ){
            $result = [
                "error" => 1,
                "msg" => "You can't provide negative value..."
            ];
        }else{
            $adj = [
                'points_credit' => 0,
                'points_debit' => 0
            ];
            $adjustmentTime = time();
            $adjustmentEntry = [
                'loyaltymember_id' => $lmember->id,
                'points_credit' => $entity->points_credit,
                'points_debit' => $entity->points_debit,
                'points_balance' => $entity->points_balance,
                'comments' => $entity->comments,
                'dated' => $entity->dated,
                'ratio' => $entity->ratio
            ];
            if($type == 'credit'){
                if($oldVal > $newVal){ // Dr the diff points to account
                    $adj['points_debit'] = $oldVal - $newVal;
                    $adjustmentText = "Adjustment done on ".date('d-m-Y h:i A',$adjustmentTime). ", " . $adj['points_debit']." points debited.";
                }else{ // Cr the diff points to account
                    $adj['points_credit'] = $newVal - $oldVal;
                    $adjustmentText = "Adjustment done on ".date('d-m-Y h:i A',$adjustmentTime). ", " . $adj['points_credit']." points credited.";
                }
            }
            if($type == 'debit'){
                if($oldVal < $newVal){ // Dr the diff points to account
                    $adj['points_debit'] = $newVal - $oldVal;
                    $adjustmentText =  "Adjustment done on ".date('d-m-Y h:i A',$adjustmentTime). ", " . $adj['points_debit']." points debited.";
                }else{ // Cr the diff points to account
                    $adj['points_credit'] = $oldVal - $newVal;
                    $adjustmentText = "Adjustment done on ".date('d-m-Y h:i A',$adjustmentTime). ", " . $adj['points_credit']." points credited.";
                }
            }
            $adjustmentEntry['points_balance'] = $adjustmentEntry['points_balance'] + $adj['points_credit'] - $adj['points_debit'];
            $adjustmentEntry['points_credit'] = $d['points_credit'];
            $adjustmentEntry['points_debit'] = $d['points_debit'];
            /** update old entry **/
            $adjustmentEntry['adjustment'] = ltrim($entity->adjustment . ";" . $adjustmentText,";");
            $entity = $tbl->patchEntity($entity, $adjustmentEntry);
            $entity = $tbl->save($entity);
            $entriesToUpdateBal = $tbl->find()->where([
                "loyaltymember_id" => $entity->loyaltymember_id,
                "id > " => $entity->id
            ])->orderAsc('id');
            $entriestoSave = [];
            $pBl = $adjustmentEntry['points_balance'];
            foreach($entriesToUpdateBal as $ent){
                $pBl = $ent->points_balance = $pBl + $ent->points_credit - $ent->points_debit;
                $tbl->save($ent);
            }
            $fnlQuery = $tbl->find()->where([
                "loyaltymember_id" => $d['loyaltymember_id']
            ]);
            $fnlResult = $fnlQuery->select([
                'totalCr' => $fnlQuery->func()->sum('points_credit'),
                'totalDr' => $fnlQuery->func()->sum('points_debit')
            ])->first();
            $lmember->set("total_points",$fnlResult->totalCr);
            $lmember->set("points_used",$fnlResult->totalDr);
            $lmember->set("points_left",$fnlResult->totalCr - $fnlResult->totalDr);
            $lmemTbl->save($lmember);
            $result = [
                "error" => 0,
                "msg" => "Points updated successfully..."
            ];
        }
        $this->set("result",$result);
        $this->set("_serialize",['result']);
    }
}