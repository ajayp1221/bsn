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

class BannersController extends AppController{
    
    public function beforeFilter(\Cake\Event\Event $event) {
         parent::beforeFilter($event);
     }

    public function index(){
        $tbl = \Cake\ORM\TableRegistry::get('Stores');
        $store = $tbl->get($this->_appSession->read('App.selectedStoreID'));
        $searchkeyword = $store->slug;
        $this->set('storeSlug' , $searchkeyword);
        $this->set('storid', $this->_appSession->read('App.selectedStoreID'));
    }
    
    public function coverlist(){
        $tbl = \Cake\ORM\TableRegistry::get('Stores');
        $store = $tbl->get($this->_appSession->read('App.selectedStoreID'));
        $searchkeyword = $store->slug;
        $client = new \Cake\Network\Http\Client();        
        $result = $client->get("http://www.zakoopi.com/api/store_cover_images/storeCoverImgListSlug/$searchkeyword.json");
        $this->response->type('json');
        $this->autoRender = false;
        $this->response->body($result->body());
    }
    
    public function coverdelete(){
        if($this->request->is('post')){
            $url = 'http://www.zakoopi.com/api/store_cover_images/deleteStoreCoverImgs.json';
            $client = new \Cake\Network\Http\Client();
            $res = $client->post($url,['id'=>$this->request->data['id']]);
            $res =1;
            if($res){
                $result = [
                    'error' => 0,
                    'msg' => 'Image Deleted Successully'
                ];
            }else{
                $result = [
                    'error' => 1,
                    'msg' => 'Something went wrong please try again'
                ];
            }
            $this->set(compact(['result']));
            $this->set('_serialize','result');
        }
    }
 
    public function images(){
        $tbl = \Cake\ORM\TableRegistry::get('Stores');
        $store = $tbl->get($this->_appSession->read('App.selectedStoreID'));
        $searchkeyword = $store->slug;
        $this->set('storeSlug' , $searchkeyword);
        $this->set('storid', $this->_appSession->read('App.selectedStoreID'));
    }
    public function bannerlist(){
        $tbl = \Cake\ORM\TableRegistry::get('Stores');
        $store = $tbl->get($this->_appSession->read('App.selectedStoreID'));
        $searchkeyword = $store->slug;
        $client = new \Cake\Network\Http\Client();
        $result = $client->get("http://www.zakoopi.com/api/stores/showStoreImgs/$searchkeyword.json");+
        $this->response->type('json');
        $this->autoRender = false;
        $this->response->body($result->body());
    }
    
    public function bannerdelete(){
        if($this->request->is('post')){
            $url = 'http://www.zakoopi.com/api/stores/deleteStoreImgs.json';
            $client = new \Cake\Network\Http\Client();
            $res = $client->post($url,['id'=>$this->request->data['id']]);
            $res =1;
            if($res){
                $result = [
                    'error' => 0,
                    'msg' => 'Banner Image Deleted Successully'
                ];
            }else{
                $result = [
                    'error' => 1,
                    'msg' => 'Something went wrong please try again'
                ];
            }
            $this->set(compact(['result']));
            $this->set('_serialize','result');
        }
    }
}