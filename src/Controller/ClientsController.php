<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 */
class ClientsController extends AppController
{

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['login', 'forgetpwd']);
    }

    public function login(){
        if ($this->request->is('post')) {
            $tblStore = \Cake\ORM\TableRegistry::get('stores');
            $client = $this->Auth->identify();
            if ($client) {
                if ($client['status'] == 0) {
                    $this->Flash->error(__('Your account has been banned'));
                    return $this->redirect('/#login/signin');
                }
                $this->Auth->setUser($client);
                if (@$this->request->data['remember']) {
                    $cookie = array();
                    $cookie['email'] = $this->request->data['email'];
                    $cookie['password'] = $this->request->data['password'];
                    $this->Cookie->write('remember', $cookie, true, "1 week");
                    unset($this->request->data['remember']);
                }
                $str = $tblStore->find()->contain(['Brands'])->where(['Brands.client_id' => $this->Auth->user('id')])->first();
                $this->_appSession->write('App.selectedStoreID', $str['id']);
                return $this->redirect('/#app/dashboard');
//                    return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Email or password is incorrect'));
                return $this->redirect('/#login/signin');
            }
        } else {
            $tblStore = \Cake\ORM\TableRegistry::get('stores');
            if ($this->Cookie->read('remember')) {
                $this->request->data = $this->Cookie->read('remember');
                $client = $this->Auth->identify();
                if ($client) {
                    $this->Auth->setUser($client);
                    
                    $str  = $tblStore->find()->contain(['Brands'])->where(['Brands.client_id'=>  $this->Auth->user('id')])->first();
                    $this->_appSession->write('App.selectedStoreID', $str['id']);
                
                    
                    return $this->redirect($this->Auth->redirectUrl());
                } else {
                    $this->Cookie->delete('remember');
                    $this->Flash->error('Invalid cookie');
                    return $this->redirect($this->Auth->redirectUrl());
                }
            }
        }
    }

    /**
     * Logout method
     *
     * @return void
     */
    public function logout() {
        $this->Cookie->delete('remember');
        return $this->redirect($this->Auth->logout());
    }

    public function setting(){
        $this->request->allowMethod(['post','put']);
        
        $result = [
            'error' => 1,
            'msg' => 'Something went wrong please try again'
        ];
        $check = 0;
        $this->loadModel('StoreSettings');
        $d = $this->request->data;
        $checkDailySmsReport = $this->StoreSettings->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'meta_key' => 'daily-sms-report'
        ])->first();
        
        if($d['active']=="true"){
            $d['daily_sms_report'] = 1;
        }else{
            $d['daily_sms_report'] =0;
        }
        if($checkDailySmsReport){
            $res = $this->StoreSettings->updateAll(['meta_value' => $d['daily_sms_report']], ['id' => $checkDailySmsReport->id]);
            if($res){
                $check = 1;
            }
        }else{
            $d1 = [
                'meta_key' => 'daily-sms-report',
                'meta_value' => $d['daily_sms_report'],
                'store_id' =>$this->_appSession->read('App.selectedStoreID')
            ];
            $d1 = $this->StoreSettings->newEntity($d1);
            $res = $this->StoreSettings->save($d1);
            if($res){
                $check = 1;
            }
        }
        
        $checkDailySmsReportOnMobile = $this->StoreSettings->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'meta_key' => 'daily-sms-report-on-mobile'
        ])->first();
        if($checkDailySmsReportOnMobile){
            $res = $this->StoreSettings->updateAll(['meta_value' => $d['mobile']], ['id' => $checkDailySmsReportOnMobile->id]);
            if($res){
                $check = 1;
            }
        }else{
            $d2 = [
                'meta_key' => 'daily-sms-report-on-mobile',
                'meta_value' => $d['mobile'],
                'store_id' =>$this->_appSession->read('App.selectedStoreID')
            ];
            $d2 = $this->StoreSettings->newEntity($d2);
            $res = $this->StoreSettings->save($d2);
            if($res){
                $check = 1;
            }
        }
        
        $checkDailySmsReportOnTime = $this->StoreSettings->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'meta_key' => 'daily-sms-report-on-what-time'
        ])->first();
        if($checkDailySmsReportOnTime){
            $res = $this->StoreSettings->updateAll(['meta_value' => $d['sms_time']], ['id' => $checkDailySmsReportOnTime->id]);
            if($res){
                $check = 1;
            }
        }else{
            $d3 = [
                'meta_key' => 'daily-sms-report-on-what-time',
                'meta_value' => $d['sms_time'],
                'store_id' =>$this->_appSession->read('App.selectedStoreID')
            ];
            $d3 = $this->StoreSettings->newEntity($d3);
            $res = $this->StoreSettings->save($d3);
            if($res){
                $check = 1;
            }
        }
        if($check==1){
            $result = [
                'error' => 0,
                'msg' => 'Changes Done Successfully'
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }
    
    public function settings(){
        $this->request->allowMethod(['post','put']);
        $this->loadModel('StoreSettings');
        $d = $this->request->data;
        $result['rec1'] = $this->StoreSettings->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'meta_key' => 'daily-sms-report'
        ])->first();
        
        $result['rec2'] = $this->StoreSettings->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'meta_key' => 'daily-sms-report-on-mobile'
        ])->first();
        
        $result['rec3'] = $this->StoreSettings->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'meta_key' => 'daily-sms-report-on-what-time'
        ])->first();
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
    }
    public function forgetpwd(){
        if($this->request->is('post')){
            $checkClient = $this->Clients->find()->where(['email' => $this->request->data['email']])->count();
            if($checkClient){
                $newpassword = "ZKP@".  rand(1000, 10000);
                $password = (new \Cake\Auth\DefaultPasswordHasher)->hash($newpassword);
                $this->Clients->updateAll(['password'=> $password,'pwd'=> $newpassword], ['email' => $this->request->data['email']]);
                $this->sendemail($this->request->data['email'],$newpassword);
                $result = [
                    'error' => 0,
                    'msg' => 'Please check your email you get new password'
                ];
            }else{
                $result = [
                    'error' => 1,
                    'msg' => "Email id doesn't exit"
                ];
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }
    
    public function sendemail($useremail=NULL,$messagecontent = NULL) {
        $clientName = $this->Clients->find()->select(['name'])->hydrate(false)->where(['email' => $useremail])->first();
        $this->request->data['fromemail'] = "alerts@zakoopi-alerts.com";
        $this->request->data['toemail'] = $useremail;
        $this->request->data['name'] = $clientName['name'];
        $this->request->data['company'] = $messagecontent;
        $methods = new \App\Common\Methods();
        $res = $methods->_sendEmail([
            $this->request->data['toemail'] => $this->request->data['name']
        ], 'Business Zakoopi Forget Password', "New Password - ".$messagecontent, 'Business-Zakoopi-ForgetPassword', ['firstname' => $this->request->data['name']]);
        return $res;
    }
    
    public function changepwd(){
        $d = $this->request->data;
        $result = [
            'error' => 1,
            'msg' => 'Something went wrong please try again'
        ];
        $client = $this->Clients->find()->where(['id' => $this->Auth->user('id')])->first();
        if ((new \Cake\Auth\DefaultPasswordHasher)->check($d['old_password'], $client->password)) {
            if($d['password']!=$d['confirm_password']){
                $result = [
                    'error' => 1,
                    'msg' => "New & Confirm password doesn't match!"
                ];
            }else{
                $d['pwd'] = $d['confirm_password'];
                $client = $this->Clients->patchEntity($client,$d);
                if ($this->Clients->save($client)) {
                    $result = [
                        'error' => 0,
                        'msg' => 'Password has been updated successfully'
                    ];
                }
            }
        }else{
            $result = [
                'error' => 1,
                'msg' => "Old password doesn't match!"
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize',['result']);
    }
    
    /*
     * Mall Setting
     */
    public function mallSetting(){
        $this->request->allowMethod(['post','put']);
        
        $result = [
            'error' => 1,
            'msg' => 'Something went wrong please try again'
        ];
        $check = 0;
        $this->loadModel('StoreSettings');
        $d = $this->request->data;
        $mallModeEnable = $this->StoreSettings->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'meta_key' => 'mallModeEnable'
        ])->first();
        
        if($mallModeEnable){
            if(!$d['mallModeEnable']){$d['mallModeEnable'] = 0;}
            $res = $this->StoreSettings->updateAll(['meta_value' => $d['mallModeEnable']], ['id' => $mallModeEnable->id]);
            if($res){
                $check = 1;
            }
        }else{
            $d1 = [
                'meta_key' => 'mallModeEnable',
                'meta_value' => $d['mallModeEnable'],
                'store_id' =>$this->_appSession->read('App.selectedStoreID')
            ];
            $d1 = $this->StoreSettings->newEntity($d1);
            $res = $this->StoreSettings->save($d1);
            if($res){
                $check = 1;
            }
        }
        
        $checkDailySmsReportOnMobile = $this->StoreSettings->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'meta_key' => 'showBillSubmitScreen'
        ])->first();
        if($checkDailySmsReportOnMobile){
            if(!$d['showBillSubmitScreen']){$d['showBillSubmitScreen'] = 0;}
            $res = $this->StoreSettings->updateAll(['meta_value' => $d['showBillSubmitScreen']], ['id' => $checkDailySmsReportOnMobile->id]);
            if($res){
                $check = 1;
            }
        }else{
            $d2 = [
                'meta_key' => 'showBillSubmitScreen',
                'meta_value' => $d['showBillSubmitScreen'],
                'store_id' =>$this->_appSession->read('App.selectedStoreID')
            ];
            $d2 = $this->StoreSettings->newEntity($d2);
            $res = $this->StoreSettings->save($d2);
            if($res){
                $check = 1;
            }
        }
        if($check==1){
            $result = [
                'error' => 0,
                'msg' => 'Changes Done Successfully'
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }
    
    public function mallSettings(){
        $this->request->allowMethod(['post','put']);
        $result = [];
        $this->loadModel('StoreSettings');
        $rec1 = $this->StoreSettings->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'meta_key' => 'showBillSubmitScreen'
        ])->first();
        if($rec1){
            $result['rec1'] = $rec1;
        }else{
            $result['rec1']['meta_value'] = 0;
        }
        $rec2 = $this->StoreSettings->find()->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'meta_key' => 'mallModeEnable'
        ])->first();
        if($rec2){
            $result['rec2'] = $rec2;
        }else{
            $result['rec2']['meta_value'] = 0;
        }
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
    }
}
