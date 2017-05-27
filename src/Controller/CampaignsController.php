<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Campaigns Controller
 *
 * @property \App\Model\Table\CampaignsTable $Campaigns
 * @property \App\Model\Table\CampaignsTable $Answers
 * @property \App\Model\Table\CustomersTable $Customers
 */
class CampaignsController extends AppController
{

    
    /**
     * Updates the Campaigns Message
     */
    public function updateMessage(){
        $this->request->allowMethod(['post']);
        $d = $this->request->data;
        $r = $this->Campaigns->updateAll([
            "message" => $d['message']
        ], [
            "id" => $d['id']
        ]);
        if($r){
            $result = [
              "msg" => "Message updated successfuly...",
              "error" => 0
            ];
        }else{
            $result = [
              "msg" => "Some error occured, Please try again...",
              "error" => 1
            ];
        }
        $this->set('result', $result);
        $this->set('_serialize', ['result']);
    }
    
    
    public function analytics(){
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
//        $d1 = new \Cake\I18n\Time(1447939036);
//        $d2 = new \Cake\I18n\Time(1448266227);
//        pr($d1);
//        pr($d2);
//        pr($d1->diffInDays($d2));
//        exit;
        
        $cmpTbl = \Cake\ORM\TableRegistry::get('Campaigns');
        $msgTbl = \Cake\ORM\TableRegistry::get('Messages');
        $campaigns = $cmpTbl->find()->select(['id','compaign_name'])->contain([
//            'Messages'
        ])->where([
            'Campaigns.store_id' => $this->_appSession->read('App.selectedStoreID'),
            'OR' => [
                'Campaigns.embedcode' => 1,
                'Campaigns.campaign_type' => 'refered'
            ]
            
        ]);
        
        if($this->request->is('post')){
            if($range = json_decode($this->request->data['range'])){
                $dateFrom = strtotime($range->start,0);
                $dateTo = strtotime($range->end,0) + 24*60*60;
            }else{
                $dateFrom = false;
                $dateTo = false;
            }
            
            $ids = $this->request->data['ids'];
            if(empty($ids))
                throw new \Cake\Network\Exception\NotFoundException('Please Provide Campaign ids...');
            
            $campaigns2 = $cmpTbl->find()->contain([
                'Messages',
                'Messages.Customers' => function($q) use($dateFrom,$dateTo){
                    if($dateFrom){
                        return $q->select(['id','age','dob'])->where(['Messages.created >= ' => $dateFrom,'Messages.created < '=>$dateTo,"Messages.promo_code <> ''"]);
                    }else{
                        return $q->select(['id','age','dob'])->where(["Messages.promo_code <> ''"]);
                    }
                }
            ])->where(['Campaigns.id IN'=>$ids])->toArray();
            foreach ($campaigns2 as $a) {
                $tmp = [
                    'overall' => [
                        'totalmsg' => 0,
                        'totalredeemed' => 0
                    ]
                ];

                foreach($a->messages as $m){
                    $tmp['overall']['totalmsg'] += 1;
                    if($m->used == 1)
                        $tmp['overall']['totalredeemed'] += 1;
                }
                $dataChart1[] = [
                    "name" => $a->compaign_name,
                    "data" => [
                        ($tmp['overall']['totalmsg'] == 0 ? 0 : ($tmp['overall']['totalredeemed'] / $tmp['overall']['totalmsg']) * 100),
                    ],
                    "totalmsg" => $tmp['overall']['totalmsg']
                ];
            }
            
            $ageWiseMsg = $cmpTbl->find()->contain([
                'Messages',
                'Messages.Customers' => function($q) use($dateFrom,$dateTo){
                    if($dateFrom){
                        return $q->select(['id','age','dob'])->where(['Messages.created >= ' => $dateFrom,'Messages.created < '=>$dateTo,"Messages.promo_code <> ''"]);
                    }else{
                        return $q->select(['id','age','dob'])->where(["Messages.promo_code <> ''"]);
                    }
                }
            ])->where(['Campaigns.id IN'=>$ids])->toArray();
            $ageWiseCmpData = [];
            foreach ($ageWiseMsg as $a){
                $tmp = [
                    'overall' => [
                        'totalmsg' => 0,
                        'totalredeemed' => 0
                    ],
                    '18t24' => [
                        'totalmsg' => 0,
                        'totalredeemed' => 0
                    ],
                    '25t32' => [
                        'totalmsg' => 0,
                        'totalredeemed' => 0
                    ],
                    '33t40' => [
                        'totalmsg' => 0,
                        'totalredeemed' => 0
                    ],
                    '40p' => [
                        'totalmsg' => 0,
                        'totalredeemed' => 0
                    ]
                ];

                foreach($a->messages as $m){
                    $tmp['overall']['totalmsg'] += 1;
                    if($m->used == 1)
                        $tmp['overall']['totalredeemed'] += 1;
                    if($m->customer->age >= 18 && $m->customer->age <= 24){
                        $tmp['18t24']['totalmsg'] += 1;
                        if($m->used == 1)
                            $tmp['18t24']['totalredeemed'] += 1;
                    }elseif($m->customer->age >= 25 && $m->customer->age <= 32){
                        $tmp['25t32']['totalmsg'] += 1;
                        if($m->used == 1)
                            $tmp['25t32']['totalredeemed'] += 1;
                    }elseif($m->customer->age >= 33 && $m->customer->age <= 40){
                        $tmp['33t40']['totalmsg'] += 1;
                        if($m->used == 1)
                            $tmp['33t40']['totalredeemed'] += 1;
                    }elseif($m->customer->age > 40){
                        $tmp['40p']['totalmsg'] += 1;
                        if($m->used == 1)
                            $tmp['40p']['totalredeemed'] += 1;
                    }
                }
                $ageWiseCmpData[] = [
                    "name" => $a->compaign_name,
                    "data" => [
                        ($tmp['overall']['totalmsg'] == 0 ? 0 : ($tmp['overall']['totalredeemed'] / $tmp['overall']['totalmsg']) * 100),
                        ($tmp['18t24']['totalmsg'] == 0 ? 0 : ($tmp['18t24']['totalredeemed'] / $tmp['18t24']['totalmsg']) * 100),
                        ($tmp['25t32']['totalmsg'] == 0 ? 0 : ($tmp['25t32']['totalredeemed'] / $tmp['25t32']['totalmsg']) * 100),
                        ($tmp['33t40']['totalmsg'] == 0 ? 0 : ($tmp['33t40']['totalredeemed'] / $tmp['33t40']['totalmsg']) * 100),
                        ($tmp['40p']['totalmsg'] == 0 ? 0 : ($tmp['40p']['totalredeemed'] / $tmp['40p']['totalmsg']) * 100),
                    ]
                ];
            }

            $genderWiseMsg = $cmpTbl->find()->contain([
                'Messages',
                'Messages.Customers' => function($q) use($dateFrom,$dateTo){
                    if($dateFrom){
                        return $q->select(['id','age','dob'])->where(['Messages.created >= ' => $dateFrom,'Messages.created < '=>$dateTo,"Messages.promo_code <> ''"]);
                    }else{
                        return $q->select(['id','age','dob'])->where(["Messages.promo_code <> ''"]);
                    }
                }
            ])->where(['Campaigns.id IN'=>$ids])->toArray();
            $genderWiseCmpData = [];
            foreach ($genderWiseMsg as $a){
                $tmp = [
                    'overall' => [
                        'totalmsg' => 0,
                        'totalredeemed' => 0
                    ],
                    'male' => [
                        'totalmsg' => 0,
                        'totalredeemed' => 0
                    ],
                    'female' => [
                        'totalmsg' => 0,
                        'totalredeemed' => 0
                    ]
                ];

                foreach($a->messages as $m){
                    $tmp['overall']['totalmsg'] += 1;
                    if($m->used == 1)
                        $tmp['overall']['totalredeemed'] += 1;

                    if($m->customer->gender == 1){
                        $tmp['male']['totalmsg'] += 1;
                        if($m->used == 1)
                            $tmp['male']['totalredeemed'] += 1;
                    }elseif($m->customer->gender == 0){
                        $tmp['female']['totalmsg'] += 1;
                        if($m->used == 1)
                            $tmp['female']['totalredeemed'] += 1;
                    }
                }
                $genderWiseCmpData[] = [
                    "name" => $a->compaign_name,
                    "data" => [
                        ($tmp['overall']['totalmsg'] == 0 ? 0 : ($tmp['overall']['totalredeemed'] / $tmp['overall']['totalmsg']) * 100),
                        ($tmp['male']['totalmsg'] == 0 ? 0 : ($tmp['male']['totalredeemed'] / $tmp['male']['totalmsg']) * 100),
                        ($tmp['female']['totalmsg'] == 0 ? 0 : ($tmp['female']['totalredeemed'] / $tmp['female']['totalmsg']) * 100),
                    ]
                ];
            }

            $timeWiseMsg = $cmpTbl->find()->contain([
                'Messages',
                'Messages.Customers' => function($q) use($dateFrom,$dateTo){
                    if($dateFrom){
                        return $q->select(['id','age','dob'])->where(['Messages.created >= ' => $dateFrom,'Messages.created < '=>$dateTo,"Messages.promo_code <> ''"]);
                    }else{
                        return $q->select(['id','age','dob'])->where(["Messages.promo_code <> ''"]);
                    }
                }
            ])->where(['Campaigns.id IN'=>$ids])->toArray();
            $timeWiseCmpData = [];
            foreach($timeWiseMsg as $a){
                $tmp = [
                    'overall' => [
                        'totalmsg' => 0,
                        'totalredeemedDaysSum' => 0,
                        'diff' => null
                    ]
                ];

                foreach($a->messages as $m){



                    if($m->used == 1 && $m->used_date != 0){
                        $tmp['overall']['totalmsg'] += 1;
                        $d1 = new \Cake\I18n\Time($m->created);
                        $d2 = new \Cake\I18n\Time($m->used_date);
                        $diff = $d1->diffInDays($d2);
                        $tmp['overall']['diff'][] = $d1." - ".$d2." = ".$diff;
                        $tmp['overall']['totalredeemedDaysSum'] += $diff;

                    }
                }
                $timeWiseCmpData[] = [
                    "name" => $a->compaign_name,
                    "label" => ($tmp['overall']['totalmsg'] == 0 ? 0 : ($tmp['overall']['totalredeemedDaysSum'] / $tmp['overall']['totalmsg'])). " day(s) (".$tmp['overall']['totalmsg'].")",
                    "data" => [
                        ($tmp['overall']['totalmsg'] == 0 ? 0 : ($tmp['overall']['totalredeemedDaysSum'] / $tmp['overall']['totalmsg']))
                    ]
                ];
            }
            $this->set('dataChart1',$dataChart1);
            $this->set('ageWiseCmpData',$ageWiseCmpData);
            $this->set('genderWiseCmpData',$genderWiseCmpData);
            $this->set('timeWiseCmpData',$timeWiseCmpData);
            $f = ['dataChart1','ageWiseCmpData','genderWiseCmpData','timeWiseCmpData'];
        }else{
            $f = ['campaigns'];
        }
        
        
        $this->set('campaigns',$campaigns);
        
        $this->set('_serialize',$f);
    }
    
    
    
    
    /**
     * Birthday Anniversary Sms method
     *
     * @return \Cake\Network\Response|null
     */

    public function birthdayAnnSms()
    {
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $limit = 20;
        $c_type = 'sms';
        $cmp = $this->Campaigns->find()->where([
            'Campaigns.campaign_type' => $c_type,
            'Campaigns.store_id' => $this->_appSession->read('App.selectedStoreID'),
            'Campaigns.soft_deleted' => 0,
            "OR" => [
                ['Campaigns.repeat_type' => 'anniversary'],
                ['Campaigns.repeat_type' => 'birthday']
            ]
        ])->orderDesc('id')->limit($limit);
        $count = $cmp->count();
        $cmp = $this->paginate($cmp);
        foreach($cmp as $c){
            $c['message_sent'] = 0;
            $c['creditused'] = 0;
            $message_sent = $this->Campaigns->Messages->find()->where(['campaign_id' => $c->id,'status' => 0])->count();
            $query = $this->Campaigns->Messages->find()->where(['campaign_id' => $c->id,'status' => 0]);
            $creditused = $query->select(['creditused' => $query->func()->sum('cost_multiplier')])->execute()->fetch('assoc')['creditused'];
            if($message_sent){$c['message_sent'] =$message_sent;}
            if($creditused){$c['creditused'] =$creditused;}
            $campaigns[] = $c;
        }
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        $this->set(compact('campaigns','pager'));
        $this->set('_serialize', ['campaigns','pager']);
    }
    
    public function birthdayAnnSave(){
        $result = [
            'error' => 1,
            'message' => 'Something went wrong please try Again'
        ];
        if($this->request->is(['post','put'])){
            $d = $this->request->data;
            $d['status'] = 1;
            $d['store_id'] = $this->_appSession->read('App.selectedStoreID');
            $d['message'] = $d['message']." Optout sms OPTZKP to 9220092200";
            /*$checkExit = $this->Campaigns->find()
                    ->where([
                        'store_id' => $this->_appSession->read('App.selectedStoreID'),
                        'repeat_type' => $d['repeat_type'],
                        'campaign_type' => $d['campaign_type']
                    ])
                    ->first();  
            if($checkExit){
                $d = $this->Campaigns->patchEntity($checkExit, $d);
            }else{
                $d = $this->Campaigns->newEntity($d);
            }
            */
            $d = $this->Campaigns->newEntity($d);
            if ($this->Campaigns->save($d)) {
                $result =  [
                    'error' => 0,
                    'message' => 'Your campagins has been added successfully!!!'
                ];
            }
        }
        $this->set(compact(['result']));
        $this->set('_serialize', ['result']);
    }

    /**
     * Birthday Anniversary Sms method
     *
     * @return \Cake\Network\Response|null
     */

    public function regularsms()
    {
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $limit = 10;
        $this->paginate = [
            'order' => [
                'Campaigns.id' => 'desc'
            ],
            'limit' => $limit,
        ];
        $c_type = 'sms';
        $cmp = $this->Campaigns->find()->where([
            'Campaigns.campaign_type' => $c_type,
            'Campaigns.store_id' => $this->_appSession->read('App.selectedStoreID'),
            'Campaigns.soft_deleted' => 0,
            "OR" => [
                ['Campaigns.repeat_type <>' => 'anniversary'],
                ['Campaigns.repeat_type <>' => 'birthday']
            ]
        ]);
        $count = $cmp->count();
        $cmp = $this->paginate($cmp);
        foreach($cmp as $c){
            $c['message_sent'] = 0;
            $c['creditused'] = 0;
            $message_sent = $this->Campaigns->Messages->find()->where(['campaign_id' => $c->id,'status' => 0])->count();
            $query = $this->Campaigns->Messages->find()->where(['campaign_id' => $c->id,'status' => 0]);
            $creditused = $query->select(['creditused' => $query->func()->sum('cost_multiplier')])->execute()->fetch('assoc')['creditused'];
            if($message_sent){$c['message_sent'] =$message_sent;}
            if($creditused){$c['creditused'] =$creditused;}
            $campaigns[] = $c;
        }
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        $this->set(compact('campaigns','pager'));
        $this->set('_serialize', ['campaigns','pager']);
    }
    
    /**
     * Birthday-Anniversary Email method
     *
     * @return \Cake\Network\Response|null
     */
    public function birthdayAnnemail()
    {
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $limit = 20;
        $c_type = 'email';
        $campaigns = $this->Campaigns->find()->contain(['Messages'])->where([
            'Campaigns.campaign_type' => $c_type,
            'Campaigns.store_id' => $this->_appSession->read('App.selectedStoreID'),
            'Campaigns.soft_deleted' => 0,
            "OR" => [
                ['Campaigns.repeat_type' => 'anniversary'],
                ['Campaigns.repeat_type' => 'birthday']
            ]
        ]);
        $count = $campaigns->count();
        $campaigns = $this->paginate($campaigns);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        $this->set(compact('campaigns','pager'));
        $this->set('_serialize', ['campaigns','pager']);
    }

    /**
     * Regular Email method
     *
     * @return \Cake\Network\Response|null
     */
    public function regularemail()
    {
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $limit = 10;
        $c_type = 'email';
        $campaigns = $this->Campaigns->find()->contain(['Messages'])->where([
            'Campaigns.campaign_type' => $c_type,
            'Campaigns.store_id' => $this->_appSession->read('App.selectedStoreID'),
            'Campaigns.soft_deleted' => 0,
            "OR" => [
                ['Campaigns.repeat_type <>' => 'anniversary'],
                ['Campaigns.repeat_type <>' => 'birthday']
            ]
        ]);
        $count = $campaigns->count();
        $campaigns = $this->paginate($campaigns);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        $this->set(compact('campaigns','pager'));
        $this->set('_serialize', ['campaigns','pager']);
    }

    /**
     * View method
     *
     * @param string|null $id Campaign id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $campaign = $this->Campaigns->get($id);
        $campaign->virtualProperties(['total_msg']);

        $this->set('campaign', $campaign);
        $this->set('_serialize', ['campaign']);
    }
    
    public function getMessages($cmpid = null){
        $msgTbl = \Cake\ORM\TableRegistry::get('Messages');
        $messages = $msgTbl->find()->contain([
            'Customers'
        ])->where([
            'Messages.campaign_id' => $cmpid
        ]);
        if($this->request->query('limit')){
            $limit = $this->request->query('limit');
        }else{
            $limit = 10;
        }
        $this->paginate = [
            'limit' => $limit
        ];
        $x = $this->paginate($messages);
        
        //**** Pager Code ****//
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $messages->count();
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        $this->set('pager', $pager);
        
        $this->set('messages', $x);
        $this->set('_serialize', ['messages','pager']);
    }

    /**
     * Add SMS Campaigns For Store
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function addSms($c_type = NULL) {
        $this->request->allowMethod(['put','post']);
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $time = explode(":", $this->request->data['send_date'])[1];
        if($time>53){
            $time = date('Y/m/d H:i', strtotime($this->request->data['send_date'] .' +1 hour'));
            $this->request->data['send_date'] = explode(":", $time)[0].":00";
        }else{
            $this->request->data['send_date'] = explode(":", $this->request->data['send_date'])[0].":00";
        }
        $d = $this->request->data;
        if($d['exceptDefaulter']){
            $conditionCst = ['Customers.contact_no !=' => '', 'Customers.is_sent' => 1, 'Customers.opt_in' => 1, 'Customers.status' => 1, 'Customers.soft_deleted' => 0];
        }else{
            $conditionCst = ['Customers.contact_no !=' => '', 'Customers.status' => 1, 'Customers.opt_in' => 1, 'Customers.soft_deleted' => 0];
        }
        $result =  [
            'error' => 1,
            'message' => 'Something went wrong please try again !!!'
        ];
        /*
         * Send Message Every Store's Customer 
         */
        $promo = "0";
        $d['store_id'] = $this->_appSession->read('App.selectedStoreID');
        $d['campaign_type'] = $c_type;
        $d['status'] = 1;
        $d['message'] = $d['message']." Optout sms OPTZKP to 9220092200";
        if($d['whos_send']==0){
            $store = $this->Campaigns->Stores->find()
               ->contain(['Customers' => function($q) use($conditionCst) {
                       return $q->where($conditionCst)->select(['Customers.id', 'Customers.store_id','Customers.contact_no']);
               }])->where(['id' => $this->_appSession->read('App.selectedStoreID')])->first();
            foreach ($store->customers as $customer) {
                $checkMobileNo = preg_match("^[789]\d{9}$^", $customer->contact_no);
                if($checkMobileNo){
                    if (isset($d['embedcode'])) {
                        $methods = new \App\Common\Methods();
                        $promo = $methods->getpromo();
                    }
                    $data[] = [
                        'customer_id' => $customer->id,
                        'store_id' => $this->_appSession->read('App.selectedStoreID'),
                        'promo_code' => $promo,
                        'status' => '1',
                    ];
                }
            }
            $d['messages'] = $data;
        }
        /*
         * Send Message Every Store's Redeemed Customer
         */
        elseif($d['whos_send'] == 1){
            $reedeamUsers = $this->Campaigns->Messages->find()->hydrate(false)
                            ->contain(['Customers'])
                            ->matching(
                                'Customers', function($q) use($conditionCst){return $q->where($conditionCst);}
                            )
                            ->select(['customer_id','store_id','used'])
                            ->where([
                                'Messages.store_id' => $this->_appSession->read('App.selectedStoreID'),
                                'Messages.used' => 1
                            ])
                            ->group(['customer_id'])
                            ->toArray();
            foreach ($reedeamUsers as $reedeamUser) {
                $checkMobileNo = preg_match("^[789]\d{9}$^", $reedeamUser['customer']['contact_no']);
                if($checkMobileNo){
                    if (isset($d['embedcode'])) {
                        $methods = new \App\Common\Methods();
                        $promo = $methods->getpromo();
                    }
                    $data[] = [
                        'customer_id' => $reedeamUser['customer_id'],
                        'store_id' => $this->_appSession->read('App.selectedStoreID'),
                        'promo_code' => time(),
                        'status' => '1',
                    ];
                }
            }
            $d['messages'] = $data;
        }
        /*
         * Send Message Static Contact Number and add to there database
         */
        elseif($d['whos_send'] == 2){
            $receivers = explode(",", $d['receivers']);
            $this->loadModel('Customers');
            foreach ($receivers as $receiver) {
                $checkMobileNo = preg_match("^[789]\d{9}$^", $receiver);
                if($checkMobileNo){
                    $checkUser = "";
                    if (isset($d['embedcode'])) {
                        $methods = new \App\Common\Methods();
                        $promo = $methods->getpromo();
                    }
                    $checkUser = $this->Customers->find()
                        ->where([
                            'store_id' =>  $this->_appSession->read('App.selectedStoreID'),
                            'contact_no' => $receiver,
                            'status' => 1,
                            'soft_deleted' => 0
                        ])
                        ->first();
                    if (empty($checkUser)) {
                        unset($user);
                        $user = [];
                        $user['contact_no'] = $receiver;
                        $user['store_id'] =  $this->_appSession->read('App.selectedStoreID');
                        $user['status'] = 1;
                        $user = $this->Customers->newEntity($user);
                        $user = $this->Customers->save($user);
                        $data[] = [
                            'customer_id' => $user->id,
                            'store_id' =>  $this->_appSession->read('App.selectedStoreID'),
                            'promo_code' => $promo,
                            'status' => '1',
                        ];
                    } else {
                        if($d['exceptDefaulter']){
                            if($checkUser->is_sent==1){
                                $data[] = [
                                    'customer_id' => $checkUser->id,
                                    'store_id' =>  $this->_appSession->read('App.selectedStoreID'),
                                    'promo_code' => $promo,
                                    'status' => '1',
                                ];
                            }
                        }else{
                            $data[] = [
                                'customer_id' => $checkUser->id,
                                'store_id' =>  $this->_appSession->read('App.selectedStoreID'),
                                'promo_code' => $promo,
                                'status' => '1',
                            ];
                        }
                    }
                    unset($receiver);
                }
            }
            $d['messages'] = $data; 
        }
        /*
         * Send Message According to customer answers on store
         */
        elseif ($d['whos_send'] == 3) {
            $customers = $this->findCstSms($d['selectedQID'],$d['selectedOpt'],$d['exceptDefaulter']);
            foreach($customers as $cus){
                if (isset($d['embedcode'])) {
                    $methods = new \App\Common\Methods();
                    $promo = $methods->getpromo();
                }
                $data[] = [
                    "customer_id" => $cus['customers_id'],
                    "store_id" => $this->_appSession->read('App.selectedStoreID'),
                    'promo_code' => $promo,
                    "status" => 1
                  ];
            }
            $d['messages'] = $data;
        }
        elseif($this->request->data['whos_send'] == 4){
            $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
            $customers = $cstTbl->find()->where([
                'Customers.tags' => $this->request->data['tags'],
                'Customers.store_id' => $this->_appSession->read('App.selectedStoreID')
            ])->all();
            $data = [];
            foreach($customers as $cus){
                $checkMobileNo = preg_match("^[789]\d{9}$^", $cus->contact_no);
                if($checkMobileNo){
                    if (isset($this->request->data['embedcode'])) {
                        $methods = new \App\Common\Methods();
                        $promo = $methods->getpromo();
                    }
                    $data[] = [
                        "customer_id" => $cus->id,
                        "store_id" => $this->_appSession->read('App.selectedStoreID'),
                        'promo_code' => $promo,
                        "status" => 1
                      ];
                }
            }
            $this->request->data['messages'] = $data;
        }elseif($this->request->data['whos_send'] == 5){
            $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
            $customers = $cstTbl->find()->where([
                'Customers.added_by' => $this->request->data['sources'],
                'Customers.store_id' => $this->_appSession->read('App.selectedStoreID')
            ])->all();
            $data = [];
            foreach($customers as $cus){
                $checkMobileNo = preg_match("^[789]\d{9}$^", $cus->contact_no);
                if($checkMobileNo){
                    if (isset($this->request->data['embedcode'])) {
                        $methods = new \App\Common\Methods();
                        $promo = $methods->getpromo();
                    }
                    $data[] = [
                        "customer_id" => $cus->id,
                        "store_id" => $this->_appSession->read('App.selectedStoreID'),
                        'promo_code' => $promo,
                        "status" => 1
                    ];
                }
            }
            $this->request->data['messages'] = $data;                
        }
        
        $con = \Cake\Datasource\ConnectionManager::get('default');
        $con->logQueries(true);
        $data = $this->Campaigns->newEntity();
        $data = $this->Campaigns->patchEntity($data, $d);
        $data = $this->Campaigns->save($data);
        if($data){
            $result =  [
                'error' => 0,
                'message' => 'Your campagins has been added successfully!!!'
            ];
        }
        $this->set(compact(['result']));
        $this->set('_serialize', ['result']);
    }
    
    /**
     * Add Email Campaigns For Store
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function addEmail($c_type = NULL) {
        $this->request->allowMethod(['put','post']);
        ini_set('memory_limit', '-1');  
        ini_set('max_execution_time', -1);
        $time = explode(":", $this->request->data['send_date'])[1];
        if($time>53){
            $time = date('Y/m/d H:i', strtotime($this->request->data['send_date'] .' +1 hour'));
            $this->request->data['send_date'] = explode(":", $time)[0].":00";
        }else{
            $this->request->data['send_date'] = explode(":", $this->request->data['send_date'])[0].":00";
        }
        $d = $this->request->data;
        $conditionCst = ['Customers.email !=' => '', 'Customers.status' => 1, 'Customers.soft_deleted' => 0];
        $result =  [
            'error' => 1,
            'message' => 'Something went wrong please try again !!!'
        ];
        /*
         * Send Email Every Store's Customer 
         */
        $promo = "0";
        $d['store_id'] = $this->_appSession->read('App.selectedStoreID');
        $d['campaign_type'] = $c_type;
        $d['status'] = 1;
        if($d['whos_send']==0){
            $store = $this->Campaigns->Stores->find()
               ->contain(['Customers' => function($q) use($conditionCst) {
                       return $q->where($conditionCst)->select(['Customers.id', 'Customers.store_id']);
               }])->where(['id' => $this->_appSession->read('App.selectedStoreID')])->first();
            foreach ($store->customers as $customer) {
                if (isset($d['embedcode'])) {
                    $methods = new \App\Common\Methods();
                    $promo = $methods->getpromo();
                }
                $data[] = [
                    'customer_id' => $customer->id,
                    'store_id' => $this->_appSession->read('App.selectedStoreID'),
                    'promo_code' => $promo,
                    'status' => '1',
                ];
            }
            $d['messages'] = $data;
        }
        /*
         * Send Email Every Store's Redeemed Customer
         */
        elseif($d['whos_send'] == 1){
            $reedeamUsers = $this->Campaigns->Messages->find()->hydrate(false)
                            ->contain(['Customers'])
                            ->matching(
                                'Customers', function($q) use($conditionCst){return $q->where($conditionCst);}
                            )
                            ->select(['customer_id','store_id','used'])
                            ->where([
                                'Messages.store_id' => $this->_appSession->read('App.selectedStoreID'),
                                'Messages.used' => 1
                            ])
                            ->group(['customer_id'])
                            ->toArray();
            foreach ($reedeamUsers as $reedeamUser) {
                if (isset($d['embedcode'])) {
                    $methods = new \App\Common\Methods();
                    $promo = $methods->getpromo();
                }
                $data[] = [
                    'customer_id' => $reedeamUser['customer_id'],
                    'store_id' => $this->_appSession->read('App.selectedStoreID'),
                    'promo_code' => time(),
                    'status' => '1',
                ];
            }
            $d['messages'] = $data;
        }
        /*
         * Send Email Static Contact Number and add to there database
         */
        elseif($d['whos_send'] == 2){
            $receivers = explode(",", $d['receivers']);
            $this->loadModel('Customers');
            foreach ($receivers as $receiver) {
                if($receiver){
                    $checkUser = "";
                    if (isset($d['embedcode'])) {
                        $methods = new \App\Common\Methods();
                        $promo = $methods->getpromo();
                    }
                    $checkUser = $this->Customers->find()
                        ->where([
                            'store_id' =>  $this->_appSession->read('App.selectedStoreID'),
                            'email' => $receiver,
                            'status' => 1,
                            'soft_deleted' => 0
                        ])
                        ->first();
                    if (empty($checkUser)) {
                        unset($user);
                        $user = [];
                        $user['email'] = $receiver;
                        $user['store_id'] =  $this->_appSession->read('App.selectedStoreID');
                        $user['status'] = 1;
                        $user = $this->Customers->newEntity($user);
                        $user = $this->Customers->save($user);
                        $data[] = [
                            'customer_id' => $user->id,
                            'store_id' =>  $this->_appSession->read('App.selectedStoreID'),
                            'promo_code' => $promo,
                            'status' => '1',
                        ];
                    } else {
                        $data[] = [
                            'customer_id' => $checkUser->id,
                            'store_id' =>  $this->_appSession->read('App.selectedStoreID'),
                            'promo_code' => $promo,
                            'status' => '1',
                        ];
                    }
                    unset($receiver);
                }
            }
            $d['messages'] = $data; 
        }
        /*
         * Send Email According to customer answers on store
         */
        elseif ($d['whos_send'] == 3) {
            $customers = $this->findCstEmail($d['selectedQID'],$d['selectedOpt']);
            foreach($customers as $cus){
                if (isset($d['embedcode'])) {
                    $methods = new \App\Common\Methods();
                    $promo = $methods->getpromo();
                }
                $data[] = [
                    "customer_id" => $cus['customers_id'],
                    "store_id" => $this->_appSession->read('App.selectedStoreID'),
                    'promo_code' => $promo,
                    "status" => 1
                  ];
            }
            $d['messages'] = $data;
        }
        $con = \Cake\Datasource\ConnectionManager::get('default');
        $con->logQueries(true);
        $data = $this->Campaigns->newEntity();
        $data = $this->Campaigns->patchEntity($data, $d);
        $data = $this->Campaigns->save($data);
        if($data){
            $result =  [
                'error' => 0,
                'message' => 'Your campagins has been added successfully!!!'
            ];
        }
        $this->set(compact(['result']));
        $this->set('_serialize', ['result']);
    }

    /**
     * Get customer Email who answered from $ans[]  question based on $q_id (exceptDefaulters Last Param)
     * @return array Array List of Customers
     */
    private function findCstEmail($qId,$ans){
        $encodeJson = json_decode($ans);
        $conditionCst = ['Customers.email !=' => '', 'Customers.status' => 1, 'Customers.soft_deleted' => 0];
        foreach($encodeJson as $key=> $a){
            $an[] = $key;
        }
        $this->loadModel('Answers');
        $customers = $this->Answers->find()
                ->hydrate(false)
                ->select(['customers_id','id'])
                ->where([
                    'question_id' => $qId,
                    'answer in' => $an,
                ])
                ->contain([
                    'Customers' => function($q) use($conditionCst){
                        return $q->where($conditionCst);
                    }
                ])
                ->group(['customers_id'])
                ->toArray();
        return $customers;
    }
    /**
     * Get customer Contact Number who answered from $ans[]  question based on $q_id (exceptDefaulters Last Param)
     * @return array Array List of Customers
     */
    private function findCstSms($qId,$ans,$isSent){
//        debug($ans);
//        $encodeJson = json_decode($ans);
        if($isSent){
            $conditionCst = ['Customers.contact_no !=' => '', 'Customers.is_sent' => 1, 'Customers.status' => 1, 'Customers.soft_deleted' => 0];
        }else{
            $conditionCst = ['Customers.contact_no !=' => '', 'Customers.status' => 1, 'Customers.soft_deleted' => 0];
        }
        foreach($ans as $key=> $a){
            $an[] = $key;
        }
        $this->loadModel('Answers');
        $customers = $this->Answers->find()
                ->hydrate(false)
                ->select(['customers_id','id'])
                ->where([
                    'question_id' => $qId,
                    'answer in' => $an,
                ])
                ->contain([
                    'Customers' => function($q) use($conditionCst){
                        return $q->where($conditionCst);
                    }
                ])
                ->group(['customers_id'])
                ->toArray();
        return $customers;
    }
    
    /**
     * Message for refer Friend Mesage List
     */
    
    public function referedList(){
        $messages = $this->Campaigns->Messages->find()->matching(
            'Campaigns',function($q){
                return $q->where([
                    'Campaigns.store_id'=> $this->_appSession->read('App.selectedStoreID'),'Campaigns.campaign_type' => 'refered'
                ])->select([
                    'Campaigns.id','Campaigns.campaign_type','Campaigns.compaign_name','Campaigns.subject','Campaigns.message','Campaigns.totalmsg',
                    'Campaigns.cost_multiplier'
                ]);
            }
        )->contain([
            'Customers' => function ($q){
                return $q->select(['id','store_id','email','contact_no','name']);
            }
        ]);
        $limit = 10;
        $this->paginate = [
            'order' => [
                'Messages.id' => 'desc'
            ],
            'limit' => $limit,
        ];
        $count = $messages->count();
        $campaigns=  $this->paginate($messages);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        $this->set(compact('messages','pager'));
        $this->set('_serialize', ['messages','pager']);
    }
    /**
     * Message for refer Friend
     */
    
    public function refered(){
        $c_type = 'refered';
        if ($this->request->is('post')) {
            $this->request->data['campaign_type'] = $c_type;
            $this->request->data['status'] = 1;
            $storeId = $this->_appSession->read('App.selectedStoreID');
            $promo = "";
            $c = 0;
            $data = $this->Campaigns->find()->where([
                'store_id' => $storeId,
                'campaign_type' => $c_type
            ])->first();
            if($data){
                $data = $this->Campaigns->patchEntity($data,  $this->request->data);
            }else{
                $data = $this->Campaigns->newEntity($this->request->data);
            }
            $data = $this->Campaigns->save($data);
            if($data){
                $result = [
                    'error' => 0,
                    'Saved successfully'
                ];
            }else{
                $result = [
                    'error' => 1,
                    'something went wrong please try again'
                ];
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }
    /**
     * Delete method
     *
     * @param string|null $id Campaign id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $campaign = $this->Campaigns->get($id);
        if ($this->Campaigns->updateAll(['soft_deleted' => 1],['id' => $id])) {
            $result = [
                'msg' => "Campaign has been deleted.",
                'error' => 0
            ];
        } else {
            $result = [
                'msg' => "Something went wrong please try again",
                'error' => 0
            ];
        }
        $this->set('result', $result);
        $this->set('_serialize',['result']);
    }
    
    /**
     * status method
     *
     * @return void
     */
    public function changeStatus() {
        $this->viewBuilder()->layout('ajax');
        $this->request->allowMethod(['post', 'put']);
        
        
        if($this->request->data['status'] == 1){
            $res = $this->Campaigns->updateAll(
            [
                'send_date' => date('Y/m/d H:00',time() + 480),
                'status'=>$this->request->data['status']
            ],[
                'id' => $this->request->data['id']
            ]);
        }else{
            $res = $this->Campaigns->updateAll(
            [
                'status'=>$this->request->data['status']
            ],[
                'id' => $this->request->data['id']
            ]);
        }
        
        if($res){
            $response['error'] = 0;
            $response['msg'] = "Status has been change scuccessfully.";
        }else {
            $response['error'] = 1;
            $response['msg'] = "Something went wrong please try again";
        }
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
    
    public function question(){
        $this->loadModel('Questions');
        $questions = $this->Questions->find()
                ->hydrate(FALSE)
                ->select([
                    'id','question','question_type'
                ])
                ->where([
                    'store_id' => $this->_appSession->read('App.selectedStoreID'),
                    'question_type <>' => '3'
                ])
                ->contain([
                    'Options',
                ])
                ->toArray();
        $this->set(compact('questions'));
        $this->set('_serialize',['questions']);
    }
    
    public function requiredCreditSms(){
        \Cake\Core\Configure::write('debug',false);
        $d = $this->request->data;
        $allCst = $this->Campaigns->Stores->find()
                ->select([
                    "Stores.id", "customersCount" => $this->Campaigns->Stores->find()->func()->count("Customers.id")
                ])
                ->matching("Customers", function($q){
                    return $q->where(['Customers.status' => 1,'Customers.contact_no !=' => '']);
                })
                ->where([
                    'Stores.id' => $this->_appSession->read('App.selectedStoreID')
                ])
                ->first();
        $result['allCst'] = $allCst->customersCount;
        $conditionCst = ['Customers.contact_no !=' => '', 'Customers.status' => 1, 'Customers.soft_deleted' => 0];
        $redeemedCst = $this->Campaigns->Messages->find()->hydrate(false)
                    ->contain(['Customers'])
                    ->matching(
                        'Customers', function($q) use($conditionCst){return $q->where($conditionCst);}
                    )
                    ->select(['customer_id','store_id','used'])
                    ->where([
                        'Messages.store_id' => $this->_appSession->read('App.selectedStoreID'),
                        'Messages.used' => 1
                    ])
                    ->group(['customer_id'])
                    ->count();
        $result['redeemedCst'] = $redeemedCst;

        $this->loadModel('Answers');
        if($d){
            $ans = $d['selectedOpt'];
//            $d["selectedQID"]= 18;
//            $ans ='{"5/10": true,"6/10": true,"7/10": true,"8/10": true,"9/10": true,"10/10": true}';
//            $encodeJson = json_decode($ans);
            foreach($ans as $key=> $a){
                $an[] = $key;
            }
            $answeredCst = $this->Answers->find()
                    ->hydrate(false)
                    ->select(['customers_id','id'])
                    ->where([
                        'question_id' => $d["selectedQID"],
                        'answer in' => $an,
                    ])
                    ->contain([
                        'Customers' => function($q){
                            return $q->where(['contact_no !='=> ""]);
                        }
                    ])
                    ->group(['customers_id'])
                    ->count();
            $result['answeredCst'] = $answeredCst;
        }
        $this->set(compact('result'));
        $this->set('_serialize',['result']);
    }
    
    public function requiredCreditEmail(){
        \Cake\Core\Configure::write('debug',false);
        $d = $this->request->data;
        $allCst = $this->Campaigns->Stores->find()
                ->select([
                    "Stores.id", "customersCount" => $this->Campaigns->Stores->find()->func()->count("Customers.id")
                ])
                ->matching("Customers", function($q){
                    return $q->where(['Customers.status' => 1,'Customers.email !=' => '']);
                })
                ->where([
                    'Stores.id' => $this->_appSession->read('App.selectedStoreID')
                ])
                ->first();
        $result['allCst'] = $allCst->customersCount;
        $conditionCst = ['Customers.email !=' => '', 'Customers.status' => 1, 'Customers.soft_deleted' => 0];
        $redeemedCst = $this->Campaigns->Messages->find()->hydrate(false)
                    ->contain(['Customers'])
                    ->matching(
                        'Customers', function($q) use($conditionCst){return $q->where($conditionCst);}
                    )
                    ->select(['customer_id','store_id','used'])
                    ->where([
                        'Messages.store_id' => $this->_appSession->read('App.selectedStoreID'),
                        'Messages.used' => 1
                    ])
                    ->group(['customer_id'])
                    ->count();
        $result['redeemedCst'] = $redeemedCst;
        
        $this->loadModel('Answers');
        if($d){
    //        $d["selectedQID"]= 18;
    //        $ans ='{"5/10": true,"6/10": true,"7/10": true,"8/10": true,"9/10": true,"10/10": true}';
            $ans = $d['selectedOpt'];
//            $encodeJson = json_decode($ans);
            foreach($ans as $key=> $a){
                $an[] = $key;
            }
            $answeredCst = $this->Answers->find()
                    ->hydrate(false)
                    ->select(['customers_id','id'])
                    ->where([
                        'question_id' => $d["selectedQID"],
                        'answer in' => $an,
                    ])
                    ->contain([
                        'Customers' => function($q){
                            return $q->where(['email !='=> ""]);
                        }
                    ])
                    ->group(['customers_id'])
                    ->count();
            $result['answeredCst'] = $answeredCst;
        }
        $this->set(compact('result'));
        $this->set('_serialize',['result']);
    }
    public function lastvisit(){
        if($this->request->is(['post','put'])){
            $d = $this->request->data;
            $d['status'] = 1;
            $d['store_id'] = $this->_appSession->read('App.selectedStoreID');
            $d['campaign_type'] = 'sms';
            $d['repeat_type'] = 'last_vist';
            $d['whos_send'] = 0;
            $d = $this->Campaigns->newEntity($d);
            if($this->Campaigns->save($d)){
                $result = [
                    'error' => 0,
                    'msg' => 'Auto reminder created successfully'
                ];
            }else{
                $result = [
                    'error' => 1,
                    'msg' => 'Somethisng went wrong please try again'
                ];
            }
        }else{
            $campaigns = $this->Campaigns->find()->where([
                'Campaigns.campaign_type' => 'sms',
                'Campaigns.store_id' => $this->_appSession->read('App.selectedStoreID'),
                'Campaigns.repeat_type' => 'last_vist'
            ])
            ->toArray();
        }
        $this->set(compact('campaigns','result'));
        $this->set('_serialize',['campaigns','result']);
    }
    
    
    public function tags(){
        $this->loadModel('Customers');
        $tags = $this->Customers->find()->select(['tags'])
                ->where(['store_id' => $this->_appSession->read('App.selectedStoreID'),'tags <>' => ""])->group('tags')->toArray();
        $this->set(compact('tags'));
        $this->set('_serialize','tags');
    }
    
    public function sources(){
        $this->loadModel('Customers');
        $sources = $this->Customers->find()->select(['added_by'])
                ->where(['store_id' => $this->_appSession->read('App.selectedStoreID'),'added_by <>' => ""])->group('added_by')->toArray();
        $this->set(compact('sources'));
        $this->set('_serialize','sources');
    }
}
