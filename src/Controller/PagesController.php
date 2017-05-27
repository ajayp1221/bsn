<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function display()
    {
        $path = func_get_args();

        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingTemplateException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }
    public function angloader(){
        $tblStore = \Cake\ORM\TableRegistry::get('stores');
        
        if ($this->Cookie->read('remember')) {
            $this->request->data = $this->Cookie->read('remember');
            $client = $this->Auth->identify();
            if ($client) {
                $this->Auth->setUser($client);

                $str  = $tblStore->find()->contain(['Brands'])->where(['Brands.client_id'=>  $this->Auth->user('id')])->first();
                $this->_appSession->write('App.selectedStoreID', $str['id']);

                return $this->redirect("/#app/dashboard");
            }
        }
    }
    
    
    public function getSmsCampaigns(){
        
        $srdCodesTbl = \Cake\ORM\TableRegistry::get('Sharedcodes');
        $srdCodes = $srdCodesTbl->find()->contain("SharedcodeRedeemed")->all();
        $srdsData = [
            "facebook" => [
                "sent" => 0,
                "credits" => 0,
                "conversions" => 0
            ],
            "twitter" => [
                "sent" => 0,
                "credits" => 0,
                "conversions" => 0
            ],
            "recommend" => [
                "sent" => 0,
                "credits" => 0,
                "conversions" => 0
            ] 
        ];
        foreach($srdCodes as $e){
            @$srdsData[$e->type]['sent'] += 1;
            @$srdsData[$e->type]['credits'] += 1;
            @$srdsData[$e->type]['conversions'] += $e->redeemed_count;
        }
        $rcmp = $srdsData;
        $cmpTable = \Cake\ORM\TableRegistry::get("Campaigns");
        $welcomeMsgTbl = \Cake\ORM\TableRegistry::get('Welcomemsgs');
        $rcmp = $cmpTable->find()->where([
            "Campaigns.store_id" => $this->_appSession->read('App.selectedStoreID'),
            "Campaigns.campaign_type IN" => ["sms","refered"]
        ]);
        
        $rcmp = $this->paginate($rcmp);
        $welcomemsg = $welcomeMsgTbl->find()->where([
            "Welcomemsgs.store_id" => $this->_appSession->read('App.selectedStoreID')
        ]);
        $wCount = $welcomemsg->count();
        if($welcomemsg->first()){
            $wr = $welcomemsg->first()->cost_multiplier;
        }else{
            $wr = 0;
        }
        $results = [];
        foreach($rcmp as $rc){
            $rc->virtualProperties(['total_delivered','total_queued','totalredeemed','total_msg','total_cost']);
            $results[] = $rc;
        }
        $count = $rcmp->count();
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = 20;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        $this->set(compact('results','pager'));
        $this->set("_serialize",['results','pager']);
    }

    
    public function getEmailCampaigns(){
        $srdCodesTbl = \Cake\ORM\TableRegistry::get('Sharedcodes');
        $srdCodes = $srdCodesTbl->find()->contain("SharedcodeRedeemed")->all();
        $srdsData = [
            "facebook" => [
                "sent" => 0,
                "credits" => 0,
                "conversions" => 0
            ],
            "twitter" => [
                "sent" => 0,
                "credits" => 0,
                "conversions" => 0
            ],
            "recommend" => [
                "sent" => 0,
                "credits" => 0,
                "conversions" => 0
            ] 
        ];
        foreach($srdCodes as $e){
            @$srdsData[$e->type]['sent'] += 1;
            @$srdsData[$e->type]['credits'] += 1;
            @$srdsData[$e->type]['conversions'] += $e->redeemed_count;
        }
        $rcmp = $srdsData;
        $cmpTable = \Cake\ORM\TableRegistry::get("Campaigns");
        $welcomeMsgTbl = \Cake\ORM\TableRegistry::get('Welcomemsgs');
        $rcmp = $cmpTable->find()->where([
            "Campaigns.store_id" => $this->_appSession->read('App.selectedStoreID'),
            "Campaigns.campaign_type IN" => ["email"]
        ]);
        $results = $this->paginate($rcmp);
        
        $welcomemsg = $welcomeMsgTbl->find()->where([
            "Welcomemsgs.store_id" => $this->_appSession->read('App.selectedStoreID')
        ]);
        $wCount = $welcomemsg->count();
        if($welcomemsg->first()){
            $wr = $welcomemsg->first()->cost_multiplier;
        }else{
            $wr = 0;
        }
        
        $count = $rcmp->count();
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager1['totalRec'] = $count;
        $pager1['step'] = 20;
        $pager1['currentPg'] = $pObj->current();
        $pager1['totalPg'] = ceil($pager1['totalRec'] / $pager1['step']);
        $this->set(compact('results','pager1'));
        $this->set("_serialize",['results','pager1']);
        
        
    }

    
    public function getSmsLedger(){
        $strTbl = \Cake\ORM\TableRegistry::get('Stores');
        $store = $strTbl->get($this->_appSession->read('App.selectedStoreID'),['contain'=>['Brands']]);
        $conn = \Cake\Datasource\ConnectionManager::get('default');
        $query = "SELECT `comments`, SUM(`credit`), SUM(`debit`), `extra`, from_unixtime(`created`) FROM `smsledger` WHERE `client_id` = ".$store->brand->client_id." GROUP BY `comments`";
        $result = $conn->query($query)->fetchAll();
        $this->set("data",$result);
        $this->set("_serialize", ['data']);
    }

        public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }
}
