<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Collection\Collection;

/**
 * Stores Controller
 *
 * @property \App\Model\Table\StoresTable $Stores
 * @property \App\Model\Table\ClientsTable $Clients
 * @property \App\Model\Table\TemplatemessagesTable $Templatemessages
 * @property \App\Model\Table\AnswersTable $Answers
 * @property \App\Model\Table\RecommendScreen $RecommendScreen
 * @property \App\Model\Table\Sharedcodes $Sharedcodes
 * @property \App\Model\Table\Excludes $Excludes
 * 
 */
class StoresController extends AppController
{

    
    public function addIosCert($store_id = null){
        if($this->request->is(['post'])){
            $d = $this->request->data;
            $name = md5($store_id).'.pem';
            $pth = WWW_ROOT .'iosPush'.DS.$name;
            if(move_uploaded_file($d['pemfile']['tmp_name'], $pth)){
                $data = [
                    'store_id' => $store_id,
                    'meta_key' => 'ios-pem',
                    'meta_value' => json_encode([
                        'pemfile' => $name,
                        'pemkey' => $d['pemkey']
                    ])
                ];
                
                $tbl = \Cake\ORM\TableRegistry::get('StoreSettings');
                $iosPem = $tbl->find()->where([
                    'StoreSettings.store_id' => $store_id,
                    'StoreSettings.meta_key' => 'ios-pem'
                ])->first();
                if($iosPem){
                    $tbl->patchEntity($iosPem, $data);
                }else{
                    $iosPem = $tbl->newEntity($data);
                }
                $tbl->save($iosPem);
            }
            $this->redirect('/admin/stores');
        }
    }




    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Brands']
        ];
        if(!empty($this->request->query['q'])){
            $searchKeyword = str_replace(' ', '%', $this->request->query['q']);
            $qry = $this->Stores->find()->where([
                'OR' => [
                    'name LIKE' => "%".$searchKeyword."%"
                ]
            ])->orderDesc('Stores.id');
        }else{
            $qry = $this->Stores->find()->orderDesc('Stores.id');
        }
        $this->set('stores', $this->paginate($qry));
        $this->set('_serialize', ['stores']);
    }

    /**
     * View method
     *
     * @param string|null $id Store id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $store = $this->Stores->get($id, [
            'contain' => ['Campaigns', 'Customers', 'Messages', 'Questions','Brands']
        ]);
        $this->set('store', $store);
        $this->set('_serialize', ['store']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $store = $this->Stores->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['status'] = '1';
            $brandInfo = $this->Stores->Brands->find()->hydrate(false)->select(['id'])->where(['brand_name' => $this->request->data['name']])->first();
            
            if($brandInfo){
                $this->request->data['brand_id'] = $brandInfo['id'];
                $store = $this->Stores->patchEntity($store, $this->request->data);
                if ($st = $this->Stores->save($store)) {
                    $xxid = $st->id;
                    $this->Flash->success(__('The store has been saved.'));
                } else {
                    $this->Flash->error(__('The store could not be saved. Please, try again.'));
                }
            }
            else{
                $data = [
                    'brand_name' => $this->request->data['name'],
                    'client_id' => $this->request->data['client_id'],
                    'status' => 1,
                    'soft_deleted' => 0,
                    'logo' => "",
                    'stores' => [
                        $this->request->data
                    ]
                ];
                $brand = $this->Stores->Brands->newEntity();
                $brand = $this->Stores->Brands->patchEntity($store, $data);
                $result = $this->Stores->Brands->save($brand);
		$xxid = $result->stores[0]->id;
                if($result){
                    $this->Flash->success(__('The store has been saved.'));
                }else{
                    $this->Flash->error(__('The store could not be saved. Please, try again.'));
                }
            }
            if(isset($xxid)){
                /* Save default Question for every Templatemessages */
                    $this->loadModel('Templatemessages');
//                $templatemessages = $this->Templatemessages->newEntity();
                $templatemessages = [
                    [
                        'store_id' => $xxid,
                        'type' => "welcome-message",
                        'message' => "Hi, thank you for registering with " . $this->request->data['name'] . ". Now stay updated with all our latest news. - " . $this->request->data['name'],
                        'status' => 1,
                        'soft_deleted' => 0
                    ],
                    [
                        'store_id' => $xxid,
                        'type' => "welcome-message-repeat",
                        'message' => "Hi! Thank you for visiting us again. Hope you had a great time shopping! - " . $this->request->data['name'],
                        'status' => 1,
                        'soft_deleted' => 0
                    ],
                    [
                        'store_id' => $xxid,
                        'type' => "welcome-message-screen-text",
                        'message' => "SIGN UP EXCITING OFFERS-".$this->request->data['name']."!",
                        'status' => 1,
                        'soft_deleted' => 0
                    ],
                    [
                        'store_id' => $xxid,
                        'type' => "discount-on-next-visit",
                        'message' => "Thank you for recommending our store. We are happy to give an additional 5% discount on your next visit.",
                        'status' => 1,
                        'soft_deleted' => 0
                    ],
                ];
                $templatemessages = $this->Templatemessages->newEntities($templatemessages);
                foreach($templatemessages as $tmpMsg){
                    $res = $this->Templatemessages->save($tmpMsg);
                }
                /* Save few default Question for every store */
                $defaultQuestions = [
                    [
                          'store_id' => $xxid,
                          'question' => 'Where did you hear about us?',
                          'question_type' => 1,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 0,
                          'no_skip' => 0,
                          'order_seq' => 1,
                          'options' => [
                                [ 'option' => 'Newspapers/Magazines' , 'status' => 1, 'soft_deleted' => 0 ],
                                [ 'option' => 'Banners/Hoardings' , 'status' => 1, 'soft_deleted' => 0 ],
                                [ 'option' => 'Zakoopi' , 'status' => 1, 'soft_deleted' => 0 ],
                                [ 'option' => 'Friends/Relatives' , 'status' => 1, 'soft_deleted' => 0 ],
                                [ 'option' => 'Never heard before' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                            
                        ],
                        [
                          'store_id' => $xxid,
                          'question' => 'How did you like our collection?',
                          'question_type' => 2,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 0,
                          'no_skip' => 0,
                          'order_seq' => 2,
                          'options' => [
                                [ 'option' => '2' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                        ],
                        [
                          'store_id' => $xxid,
                          'question' => 'Are you happy with our price range?',
                          'question_type' => 2,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 0,
                          'no_skip' => 0,
                          'order_seq' => 3,
                          'options' => [
                                [ 'option' => '2' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                        ],
                        [
                          'store_id' => $xxid,
                          'question' => 'Are you going to recommend our store? Give us a rating out of 10 (10 for Excellent)',
                          'question_type' => 2,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => 'nps',
                          'no_delete' => 1,
                          'no_skip' => 1,
                          'order_seq' => 4,
                          'options' => [
                                [ 'option' => '3' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                        ],
//                        [
//                          'store_id' => $xxid,
//                          'question' => 'Please give our store a rating out of 10',
//                          'question_type' => 2,
//                          'status' =>  1,
//                          'soft_deleted' => 0,
//                          'view_type' => 'rating',
//                          'no_delete' => 1,
//                          'no_skip' => 1,
//                          'options' => [
//                                [ 'option' => '3' , 'status' => 1, 'soft_deleted' => 0 ]
//                              ]
//                        ], 
                        [
                          'store_id' => $xxid,
                          'question' => 'Please share your feedback with us.',
                          'question_type' => 3,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => 'review',
                          'no_delete' => 1,
                          'no_skip' => 0,
                          'order_seq' => 5,
                        ],
//                        [
//                          'store_id' => $xxid,
//                          'question' => 'Do you wish to leave any comments for us?',
//                          'question_type' => 3,
//                          'status' =>  1,
//                          'soft_deleted' => 0,
//                          'view_type' => '',
//                          'no_delete' => 0,
//                          'no_skip' => 0
//                        ],
                    ];
                    $queTbl = \Cake\ORM\TableRegistry::get('Questions');
                    $entities = $queTbl->newEntities($defaultQuestions, ['associated' => ['Options']]);
                    foreach ($entities as $entity) {
                        $queTbl->save($entity);
                    }
                    
                    $defaultCampaign = [
                        'store_id' => $xxid,
                        'campaign_type' => 'refered',
                        'send_before_day' => 0,
                        'send_date' => '',
                        'repeat' =>  1,
                        'repeat_type' => '',
                        'compaign_name' => 'Recommend Friends SMS Campaign',
                        'message' => 'Hi {{cstName}}! Your friend {{frndName}} recommends a visit to {{storeName}}. Show this SMS for 5% off. {{ZakoopiStoreShortLink}} ',
                        'whos_send' => 0,
                        'status' => 1,
                        'soft_deleted' => 0,
                        'embedcode' => 0
                    ];
                    $cmpTbl = \Cake\ORM\TableRegistry::get('Campaigns');
                    $cmpEnt = $cmpTbl->newEntity($defaultCampaign);
                    $cmpTbl->save($cmpEnt);
                    
                    $defaultShareScreen = [
                        'store_id' => $xxid,
                        'active' => 1,
                        'header_text' => 'GET 5% CASHBACK NOW !!!',
                        'description' => 'Recommend store to your friends to get 5% cashback on the current bill.',
                        'popup_title' =>  'XYZ Store',
                        'popup_message' => 'A very well organised store with the availability of saree suits and lehengas. Why to visit the store is because of the availability of all kinds of designer bridal lehengas with an option of customization. Because the store is so organised it is very easy to shop specifically what is needed. A wide range of products also is a big plus point.',
                        'popup_url' => 'http://www.zakoopi.com',
                        'created' => time()
                    ];
                    $shrScrTbl = \Cake\ORM\TableRegistry::get('ShareScreen');
                    $shrScrEnt = $shrScrTbl->newEntity($defaultShareScreen);
                    $shrScrTbl->save($shrScrEnt);
                    
                    $recScrScreen = [
                        'store_id' => $xxid,
                        'active' => 1,
                        'header_text' => 'GET 5% CASHBACK NOW !!!',
                        'description' => 'Recommend store to your friends to get 5% cashback on the current bill.',
                        'created' => time()
                    ];
                    $recScrTbl = \Cake\ORM\TableRegistry::get('RecommendScreen');
                    $recScrEnt = $recScrTbl->newEntity($recScrScreen);
                    $recScrTbl->save($recScrEnt);
                    
                    
                    $settTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
                    $entities = $settTbl->newEntities([
                        [
                            'store_id' => $xxid,
                            'meta_key' => 'r-screen-customer-discount',
                            'meta_value' => '0'
                        ],
                        [
                            'store_id' => $xxid,
                            'meta_key' => 'r-screen-customer-discount-msg',
                            'meta_value' => 'Thank you for promoting us.'
                        ],
                        [
                            'store_id' => $xxid,
                            'meta_key' => 's-screen-customer-discount',
                            'meta_value' => '0'
                        ],
                        [
                            'store_id' => $xxid,
                            'meta_key' => 's-screen-customer-discount-msg',
                            'meta_value' => 'Thank you for promoting us on social media.'
                        ],
                        [
                            'store_id' => $xxid,
                            'meta_key' => 'daily-sms-report',
                            'meta_value' => '0'
                        ],
                        [
                            'store_id' => $xxid,
                            'meta_key' => 'mallModeEnable',
                            'meta_value' => '0'
                        ],
                        [
                            'store_id' => $xxid,
                            'meta_key' => 'showBillSubmitScreen',
                            'meta_value' => '0'
                        ],
                    ]);
                    foreach($entities as $ent){
                        $settTbl->save($ent);
                    }                   
                    
                    return $this->redirect(['action' => 'index']);
            }
        }
        $this->loadModel('Clients');
        $clients = $this->Clients->find('list', ['limit' => 200])->order(['id' =>'desc']);
        $this->set(compact('store', 'clients'));
        $this->set('_serialize', ['store']);
    }
    
    
    
    
    
    
    
    
    
    
    
    public function add1()
    {
        $store = $this->Stores->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['status'] = '1';
            $brandInfo = $this->Stores->Brands->find()->hydrate(false)->select(['id'])->where(['brand_name' => $this->request->data['name']])->first();
            
            if($brandInfo){
                $this->request->data['brand_id'] = $brandInfo['id'];
                $store = $this->Stores->patchEntity($store, $this->request->data);
                if ($st = $this->Stores->save($store)) {
                    $xxid = $st->id;
                    $this->Flash->success(__('The store has been saved.'));
                } else {
                    $this->Flash->error(__('The store could not be saved. Please, try again.'));
                }
            }
            else{
                $data = [
                    'brand_name' => $this->request->data['name'],
                    'client_id' => $this->request->data['client_id'],
                    'status' => 1,
                    'soft_deleted' => 0,
                    'logo' => "",
                    'stores' => [
                        $this->request->data
                    ]
                ];
                $brand = $this->Stores->Brands->newEntity();
                $brand = $this->Stores->Brands->patchEntity($store, $data);
                $result = $this->Stores->Brands->save($brand);
		$xxid = $result->stores[0]->id;
                if($result){
                    $this->Flash->success(__('The store has been saved.'));
                }else{
                    $this->Flash->error(__('The store could not be saved. Please, try again.'));
                }
            }
            if(isset($xxid)){
                /* Save default Question for every Templatemessages */
                    $this->loadModel('Templatemessages');
//                $templatemessages = $this->Templatemessages->newEntity();
                $templatemessages = [
                    [
                        'store_id' => $xxid,
                        'type' => "welcome-message",
                        'message' => "Hi, thank you for registering with " . $this->request->data['name'] . ". Now stay updated with all our latest news. - " . $this->request->data['name'],
                        'status' => 1,
                        'soft_deleted' => 0
                    ],
                    [
                        'store_id' => $xxid,
                        'type' => "welcome-message-repeat",
                        'message' => "Hi! Thank you for visiting us again. Hope you had a great time shopping! - " . $this->request->data['name'],
                        'status' => 1,
                        'soft_deleted' => 0
                    ],
                    [
                        'store_id' => $xxid,
                        'type' => "welcome-message-screen-text",
                        'message' => "SIGN UP EXCITING OFFERS-".$this->request->data['name']."!",
                        'status' => 1,
                        'soft_deleted' => 0
                    ],
                    [
                        'store_id' => $xxid,
                        'type' => "discount-on-next-visit",
                        'message' => "Thank you for recommending our store. We are happy to give an additional 5% discount on your next visit.",
                        'status' => 1,
                        'soft_deleted' => 0
                    ],
                ];
                $templatemessages = $this->Templatemessages->newEntities($templatemessages);
                foreach($templatemessages as $tmpMsg){
                    $res = $this->Templatemessages->save($tmpMsg);
                }
                /* Save few default Question for every store */
                $defaultQuestions = [
                    [
                          'store_id' => $xxid,
                          'question' => 'Where did you hear about us?',
                          'question_type' => 1,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 0,
                          'no_skip' => 0,
                          'order_seq' => 1,
                          'options' => [
                                [ 'option' => 'Newspapers/Magazines' , 'status' => 1, 'soft_deleted' => 0 ],
                                [ 'option' => 'Banners/Hoardings' , 'status' => 1, 'soft_deleted' => 0 ],
                                [ 'option' => 'Zakoopi' , 'status' => 1, 'soft_deleted' => 0 ],
                                [ 'option' => 'Friends/Relatives' , 'status' => 1, 'soft_deleted' => 0 ],
                                [ 'option' => 'Never heard before' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                            
                        ],
                        [
                          'store_id' => $xxid,
                          'question' => 'How did you like our collection?',
                          'question_type' => 2,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 0,
                          'no_skip' => 0,
                          'order_seq' => 2,
                          'options' => [
                                [ 'option' => '2' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                        ],
                        [
                          'store_id' => $xxid,
                          'question' => 'Are you happy with our price range?',
                          'question_type' => 2,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 0,
                          'no_skip' => 0,
                          'order_seq' => 3,
                          'options' => [
                                [ 'option' => '2' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                        ],
                        [
                          'store_id' => $xxid,
                          'question' => 'Are you going to recommend our store? Give us a rating out of 10 (10 for Excellent)',
                          'question_type' => 2,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => 'nps',
                          'no_delete' => 1,
                          'no_skip' => 1,
                          'order_seq' => 4,
                          'options' => [
                                [ 'option' => '3' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                        ],
//                        [
//                          'store_id' => $xxid,
//                          'question' => 'Please give our store a rating out of 10',
//                          'question_type' => 2,
//                          'status' =>  1,
//                          'soft_deleted' => 0,
//                          'view_type' => 'rating',
//                          'no_delete' => 1,
//                          'no_skip' => 1,
//                          'options' => [
//                                [ 'option' => '3' , 'status' => 1, 'soft_deleted' => 0 ]
//                              ]
//                        ], 
                        [
                          'store_id' => $xxid,
                          'question' => 'Please share your feedback with us.',
                          'question_type' => 3,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => 'review',
                          'no_delete' => 1,
                          'no_skip' => 0,
                          'order_seq' => 5,
                        ],
//                        [
//                          'store_id' => $xxid,
//                          'question' => 'Do you wish to leave any comments for us?',
//                          'question_type' => 3,
//                          'status' =>  1,
//                          'soft_deleted' => 0,
//                          'view_type' => '',
//                          'no_delete' => 0,
//                          'no_skip' => 0
//                        ],
                    ];
                    $queTbl = \Cake\ORM\TableRegistry::get('Questions');
                    $entities = $queTbl->newEntities($defaultQuestions, ['associated' => ['Options']]);
                    foreach ($entities as $entity) {
                        $queTbl->save($entity);
                    }
                    
                    $defaultCampaign = [
                        'store_id' => $xxid,
                        'campaign_type' => 'refered',
                        'send_before_day' => 0,
                        'send_date' => '',
                        'repeat' =>  1,
                        'repeat_type' => '',
                        'compaign_name' => 'Recommend Friends SMS Campaign',
                        'message' => 'Hi {{cstName}}! Your friend {{frndName}} recommends a visit to {{storeName}}. Show this SMS for 5% off. {{ZakoopiStoreShortLink}} ',
                        'whos_send' => 0,
                        'status' => 1,
                        'soft_deleted' => 0,
                        'embedcode' => 0
                    ];
                    $cmpTbl = \Cake\ORM\TableRegistry::get('Campaigns');
                    $cmpEnt = $cmpTbl->newEntity($defaultCampaign);
                    $cmpTbl->save($cmpEnt);
                    
                    $defaultShareScreen = [
                        'store_id' => $xxid,
                        'active' => 1,
                        'header_text' => 'GET 5% CASHBACK NOW !!!',
                        'description' => 'Recommend store to your friends to get 5% cashback on the current bill.',
                        'popup_title' =>  'XYZ Store',
                        'popup_message' => 'A very well organised store with the availability of saree suits and lehengas. Why to visit the store is because of the availability of all kinds of designer bridal lehengas with an option of customization. Because the store is so organised it is very easy to shop specifically what is needed. A wide range of products also is a big plus point.',
                        'popup_url' => 'http://www.zakoopi.com',
                        'created' => time()
                    ];
                    $shrScrTbl = \Cake\ORM\TableRegistry::get('ShareScreen');
                    $shrScrEnt = $shrScrTbl->newEntity($defaultShareScreen);
                    $shrScrTbl->save($shrScrEnt);
                    
                    $recScrScreen = [
                        'store_id' => $xxid,
                        'active' => 1,
                        'header_text' => 'GET 5% CASHBACK NOW !!!',
                        'description' => 'Recommend store to your friends to get 5% cashback on the current bill.',
                        'created' => time()
                    ];
                    $recScrTbl = \Cake\ORM\TableRegistry::get('RecommendScreen');
                    $recScrEnt = $recScrTbl->newEntity($recScrScreen);
                    $recScrTbl->save($recScrEnt);
                    
                    
                    $settTbl = \Cake\ORM\TableRegistry::get('StoreSettings');
                    $entities = $settTbl->newEntities([
                        [
                            'store_id' => $xxid,
                            'meta_key' => 'r-screen-customer-discount',
                            'meta_value' => '0'
                        ],
                        [
                            'store_id' => $xxid,
                            'meta_key' => 'r-screen-customer-discount-msg',
                            'meta_value' => 'Thank you for promoting us.'
                        ],
                        [
                            'store_id' => $xxid,
                            'meta_key' => 's-screen-customer-discount',
                            'meta_value' => '0'
                        ],
                        [
                            'store_id' => $xxid,
                            'meta_key' => 's-screen-customer-discount-msg',
                            'meta_value' => 'Thank you for promoting us on social media.'
                        ],
                        [
                            'store_id' => $xxid,
                            'meta_key' => 'daily-sms-report',
                            'meta_value' => '0'
                        ],
                        [
                            'store_id' => $xxid,
                            'meta_key' => 'mallModeEnable',
                            'meta_value' => '0'
                        ],
                        [
                            'store_id' => $xxid,
                            'meta_key' => 'showBillSubmitScreen',
                            'meta_value' => '0'
                        ],
                    ]);
                    foreach($entities as $ent){
                        $settTbl->save($ent);
                    }                   
                    
                    return $this->redirect(['action' => 'index']);
            }
        }
        $this->loadModel('Clients');
        $clients = $this->Clients->find('list', ['limit' => 200])->order(['id' =>'desc']);
        $this->set(compact('store', 'clients'));
        $this->set('_serialize', ['store']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Store id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $store = $this->Stores->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $store = $this->Stores->patchEntity($store, $this->request->data);
            if ($this->Stores->save($store)) {
                $this->Flash->success(__('The store has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The store could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('store'));
        $this->set('_serialize', ['store']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Store id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
//        $this->request->allowMethod(['post', 'delete']);
//        $softDeleted = $this->Stores->updateAll(['soft_deleted' => 1], ['id' => $id]);
//        if ($softDeleted) {
//            $this->Flash->success(__('The store has been deleted.'));
//        } else {
//            $this->Flash->error(__('The store could not be deleted. Please, try again.'));
//        }
//        return $this->redirect($this->referer());
        $this->request->allowMethod(['post', 'delete']);
        $store = $this->Stores->get($id);
        if ($this->Stores->delete($store)) {
            $this->Flash->success(__('The store has been deleted.'));
        } else {
            $this->Flash->error(__('The store could not be deleted. Please, try again.'));
        }
        return $this->redirect($this->referer());
    }
    
    public function search(){
        $searchkeyword = $this->request->query['query'];
        $result = $this->curl("http://www.zakoopi.com/api/stores/allStoreSearch.json?term=$searchkeyword");
        $this->response->type('json');
        $this->autoRender = false;
        $this->response->body($result);
//        $this->set(compact('result'));
//        $this->set('_serialize', 'result');
    }
    
    private function curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    
    public function report(){
        $stDt = 1446531585;
        $edDt = time();
        \Cake\Core\Configure::write('debug',TRUE);
        if(@$this->request->query['st'] && @$this->request->query['ed']){
            \Cake\Core\Configure::write('debug',TRUE);
            $sts = $this->request->query['st']."00:00:00";
            $eds = $this->request->query['ed']."23:23:59";
            $stDt = strtotime($sts);
            $edDt = strtotime($eds);
        }
        $this->loadModel('Excludes');
        $storeIdNot = $this->Excludes->find('list',['valueField' => 'value'])->where(['model_name'=>'Stores'])->toArray();
        if(!$storeIdNot){
            $storeIdNot = [2];
        }
        $contactNotIn = $this->Excludes->find('list',['valueField' => 'value'])->where(['model_name'=>'Customers'])->toArray();
        if(!$contactNotIn){
            $contactNotIn = ['7838283001'];
        }
        $storesFind = $this->Stores->find()
                ->hydrate(false)
                ->select([
                    'id', 'name'
                ])
                ->contain([
                    'Brands' => function($q){
                        return $q->select(['id','client_id']);
                    },
                    'Questions' => function($q){
                        return $q->select(['id','store_id']);
                    }
                ])
                ->where([
                    'Stores.id NOT IN' => $storeIdNot
                ])
                ->toArray();
        $this->loadModel('Answers');
        $this->loadModel('RecommendScreen');
        $this->loadModel('Sharedcodes');
		
        foreach($storesFind as $storeFind){
            $customers = 0;
            $numbers = (new Collection($storeFind['questions']))->extract('id');
            $numbers = $numbers->toList();
            $chCst = $this->Stores->Customers->find('list',['valueField' => 'id'])
                    ->where([
                            'contact_no In' => $contactNotIn,
                            'store_id' => $storeFind['id']
                    ])
                    ->toArray();
            if($chCst){
                    $answers = $this->Answers->find()
                            ->where([
                                    'Answers.question_id IN ' => $numbers,
                                    'customers_id NOT IN' => $chCst,
                                    'created >=' => $stDt,
                                    'created <=' => $edDt
                            ])
                            ->hydrate(false)
                            ->group(['customers_id'])
                            ->count();
            }else{
                    $answers = $this->Answers->find()
                            ->where([
                                    'Answers.question_id IN ' => $numbers,
                                    'created >=' => $stDt,
                                    'created <=' => $edDt
                            ])
                            ->hydrate(false)
                            ->group(['customers_id'])
                            ->count();
            }
            $recomemdedCustomer = $this->Stores->Customers->find()
                ->where([
                    'refered_by <>' => 0,
                    'store_id' => $storeFind['id'],
                    'created >=' => $stDt,
                    'created <=' => $edDt,
                    'contact_no NOT IN' => $contactNotIn
                ])->count();
            
            $totalCustomer = $this->Stores->Customers->find()
                ->where([
                    'store_id' => $storeFind['id'],
                    'refered_by' => 0,
                    'added_by LIKE' => "%MOBILE%",
                    'created >=' => $stDt,
                    'created <=' => $edDt,
                    'contact_no NOT IN' => $contactNotIn
                ])->count();
            $totalSharedcodes =  $this->Sharedcodes->find()
                    ->where([
                        'client_id' => $storeFind['brand']['client_id'],
                        'created_at >=' => $stDt,
                        'created_at <=' => $edDt
                    ])->count();
            $storeFind['answer'] = $answers;
            $storeFind['recomemdedCustomer'] = $recomemdedCustomer;
            $storeFind['totalCustomer'] = $totalCustomer;
            $storeFind['totalSharedcodes'] = $totalSharedcodes;
            unset($storeFind['questions']);
            $stores[] = $storeFind;
        }
        $this->set(compact('stores'));
        $this->set('_serialize', ['stores']);
    }
}