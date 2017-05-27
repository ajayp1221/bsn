<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ShareScreen Controller
 *
 * @property \App\Model\Table\ShareScreenTable $ShareScreen
 */
class ShareScreenController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $shareScreen = $this->ShareScreen->find()->where(['store_id' => $this->_appSession->read('App.selectedStoreID')])->first();
        $this->set(compact('shareScreen'));
        $this->set('_serialize', ['shareScreen']);
    }

    /**
     * View method
     *
     * @param string|null $id Share Screen id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shareScreen = $this->ShareScreen->get($id, [
            'contain' => ['Stores']
        ]);

        $this->set('shareScreen', $shareScreen);
        $this->set('_serialize', ['shareScreen']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->is(['post','put'])) {
            $result = [
                'error' => 1,
                'msg' => 'some thing went wrong please try again'
            ];
            $d = $this->request->data;
            if($d['active']){$d['active'] = 1;}else{$d['active'] = 0;}
            if($d['id']){
                $shareScreen = $this->ShareScreen->find()->where(['store_id' => $this->_appSession->read('App.selectedStoreID')])->first();
            }else{
                $shareScreen = $this->ShareScreen->newEntity();
            }
            $shareScreen = $this->ShareScreen->patchEntity($shareScreen, $d);
            if ($result = $this->ShareScreen->save($shareScreen)) {
                $result = [
                    'error' => 0,
                    'msg' => 'Change done successfully'
                ];
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }

    /**
     * Give discount in next visit / Give discount in present visit 
     * while Sharing Code on Social Media
     * 
     */
    public function changeDiscountOn($setData = null){
        $str_id = $this->_appSession->read('App.selectedStoreID');
        $settingsTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
        $d = $this->request->data;
        
        $rscd = $settingsTbl->find()->where([
            "store_id" => $str_id,
            "meta_key" => 's-screen-customer-discount'
        ])->first();
        if(!$rscd){
            $rscd = $settingsTbl->newEntity();
        }
        
        $rscdm = $settingsTbl->find()->where([
            "store_id" => $str_id,
            "meta_key" => 's-screen-customer-discount-msg'
        ])->first();
        if(!$rscdm){
            $rscdm = $settingsTbl->newEntity();
        }
        
        if($setData != NULL){
            $data = [
                "store_id" => $str_id,
                "meta_key" => "s-screen-customer-discount",
                "meta_value" => $d['s-screen-customer-discount'],
            ];
            $rscd = $settingsTbl->patchEntity($rscd, $data);
            $settingsTbl->save($rscd);
            if($d['s-screen-customer-discount']."" == "1"){
                $data = [
                    "store_id" => $str_id,
                    "meta_key" => "s-screen-customer-discount-msg",
                    "meta_value" => $d['s-screen-customer-discount-msg'],
                ];
                $rscdm = $settingsTbl->patchEntity($rscdm, $data);
                $settingsTbl->save($rscdm);
            }
            $result = [
                "msg" => "Updated...",
                "error" => 0
            ];
        }else{
            $result = ["a"=>$rscd->toArray(),"b"=>$rscdm->toArray()];
        }
        $this->set('result',$result);
        $this->set('_serialize',['result']);
    }
}
