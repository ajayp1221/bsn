<?php
namespace App\Controller;
use App\Controller\AppController;
/**
 * Description of FeedbackController
 *
 * @author Himanshu
 */
class FeedbackController extends AppController{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
    }
    public function m($questionId = null){
        
//        debug($this->request->query('range')); exit;
        if($range = json_decode($this->request->query('range'))){
            $dateFrom = strtotime($range->start,0);
            $dateTo = strtotime($range->end,0) + 24*60*60;
        }else{
            $dateFrom = false;
            $dateTo = false;
        }
        $selectedQue = [];
        
        $storeId = $this->_appSession->read('App.selectedStoreID');
        $quesTbl = \Cake\ORM\TableRegistry::get('Questions');
        
        if($questionId){
            $questionId = base64_decode(base64_decode($questionId));
            $selectedQue = $quesTbl->find()->contain([
                'Answers' => function($q) use($dateFrom,$dateTo){
                    if($dateFrom){
                        return $q->where(["Answers.created >= "=>$dateFrom,"Answers.created < " => $dateTo]);
                    }else{
                        return $q;
                    }
                },
                'Answers.Customers'
            ])->where([
                'Questions.id' => $questionId
            ])->first();
            $data = [];
            $totalRespondents = 0;
            foreach($selectedQue->answers as $sq){
                $totalRespondents++;
                if($sq->answer_type == 3){
                    $data[] = [
                        "title" => $sq->customer->name ." (" . $sq->customer->contact_no . ")",
                        "content" => $sq->answer,
                        "created" => $sq->created
                    ];
                }
                if($sq->answer_type == 2){
                    $data[] = [
                        "title" => $sq->customer->name ." (" . $sq->customer->contact_no . ")".$sq->id,
                        "content" => explode("/", $sq->answer),
                        "created" => is_numeric($sq->created) ? date('d-m',$sq->created) : date('d-m',strtotime($sq->created))// $sq->created//date('d-m',$sq->created)
                    ];
//                    pr($data->toArray());
                }
                if($sq->answer_type == 1){
                    if(isset($data[$sq->answer])){
                        $data[$sq->answer] += 1;
                    }else{
                        $data[$sq->answer] = 1;
                    }
                }
                
            }
            if($selectedQue->question_type == 2){
                $d1 = new \Cake\Collection\Collection($data);
                $d1 = $d1->groupBy('created');
                $data = $d1->toArray();
//                pr($data->toArray());
            }
//            $answers = new \Cake\Collection\Collection($selectedQue->answers);
//            $x = $answers->groupBy('answer');
//            pr($data);
//            pr($selectedQue->toArray()); exit;
//            pr(json_decode(json_encode($selectedQue),JSON_PRETTY_PRINT)); exit;
//            pr($data); exit;
            
            $this->set('data',$data);
            $this->set('respondents',$totalRespondents);
            $this->set('selectedQue',$selectedQue);
            $questions = $quesTbl->find()->where([
                'Questions.store_id' => $storeId,
                'Questions.soft_deleted' => 0
            ])->all();
            $this->set('questions', $questions);
            $this->set('selectedQue',$selectedQue);
            $this->set('_serialize',['questions','selectedQue','data','respondents']);
            $this->render('m'.$selectedQue->question_type);
        }
        $questions = $quesTbl->find()->where([
            'Questions.store_id' => $storeId,
            'Questions.soft_deleted' => 0
        ])->all();
        $this->set('selectedQue',$selectedQue);
        $this->set('questions', $questions);
        $this->set('_serialize',['questions','selectedQue']);
    }
    
    
    public function nps(){
        if($range = json_decode($this->request->query('range'))){
            $dateFrom = strtotime($range->start,0);
            $dateTo = strtotime($range->end,0) + 24*60*60;
        }else{
            $dateFrom = false;
            $dateTo = false;
        }
        $queTbl = \Cake\ORM\TableRegistry::get('Questions');
        $npsQue = $queTbl->find()->where([
            'Questions.view_type' => 'nps',
            'Questions.store_id' => $this->_appSession->read('App.selectedStoreID')
        ])->first();
        if(!$npsQue){
//            $this->Flash->error("It Seems NPS Analysis is not in your Package...");
//            $this->redirect($this->referer());
            throw new \Cake\Network\Exception\NotFoundException("It Seems NPS Analysis is not in your Package...");
//                    $this->response->httpCodes(['404' => "It Seems NPS Analysis is not in your Package..."]);
        }
        
        $ansTbl = \Cake\ORM\TableRegistry::get('Answers');
        $conditions = [
            'Answers.question_id' => $npsQue->id,
        ];
        if($dateFrom){
            $conditions['Answers.created > '] = $dateFrom;
            $conditions['Answers.created <= '] = $dateTo;
        }
        $answers = $ansTbl->find()->contain([
            "Customers" => function($q){
                return $q->select(['id','dob','age','gender']);
            }
        ])->where($conditions);
        $result = [
            "gender" => [
                "male" => [
                    "total" => 0,
                    "promoters" => 0
                ],
                "female" => [
                    "total" => 0,
                    "promoters" => 0
                ],
                "unknown" => [
                    "total" => 0,
                    "promoters" => 0
                ],
            ],
            "age" => [
                "18t24" => [
                    "total" => 0,
                    "promoters" => 0
                ],
                "25t32" => [
                    "total" => 0,
                    "promoters" => 0
                ],
                "33t40" => [
                    "total" => 0,
                    "promoters" => 0
                ],
                "40p" => [
                    "total" => 0,
                    "promoters" => 0
                ],
            ],
            "demoters" => [
                "count" => 0,
                "data" => [],
            ],
            "passive" => [
                "count" => 0,
                "data" => []
            ],
            "promoters" => [
                "count" => 0,
                "data" => [],
            ],
            "totalcounts" => 0
        ];
        foreach ($answers as $a){
            $val = explode("/", $a->answer);
            
            /* Age Total Answers Count */
            if($a->customer->age >= 18 && $a->customer->age <= 24){
                $result['age']['18t24']['total'] += 1;
            }elseif($a->customer->age >= 25 && $a->customer->age <= 32){
                $result['age']['25t32']['total'] += 1;
            }elseif($a->customer->age >= 33 && $a->customer->age <= 40){
                $result['age']['33t40']['total'] += 1;
            }elseif($a->customer->age > 40){
                $result['age']['40p']['total'] += 1;
            }
            
            
            /* Gender Total Answers Count */
            if($a->customer->gender == 0){
                $result['gender']['female']['total'] += 1;
            }elseif($a->customer->gender == 1){
                $result['gender']['male']['total'] += 1;
            }else{
                $result['gender']['unknown']['total'] += 1;
            }
            
            /* Promotors Calculation Algo */
            if($val[0] < 7){
                $result['demoters']['count'] += 1;
                $result['demoters']['data'][is_numeric($a->created) ? date('d-m',$a->created) : date('d-m',strtotime($a->created))][] = $a;
            }elseif($val[0] < 9){
                $result['passive']['count'] += 1;
                $result['passive']['data'][is_numeric($a->created) ? date('d-m',$a->created) : date('d-m',strtotime($a->created))][] = $a;
            }elseif($val[0] < 11){
                $result['promoters']['count'] += 1;
                $result['promoters']['data'][is_numeric($a->created) ? date('d-m',$a->created) : date('d-m',strtotime($a->created))][] = $a;
                
                /* Gender Total Promoters Count */
                if($a->customer->gender == 0){
                    $result['gender']['female']['promoters'] += 1;
                }elseif($a->customer->gender == 1){
                    $result['gender']['male']['promoters'] += 1;
                }else{
                    $result['gender']['unknown']['promoters'] += 1;
                }
                
                /* Age Total Promoters Count */
                if($a->customer->age >= 18 && $a->customer->age <= 24){
                    $result['age']['18t24']['promoters'] += 1;
                }elseif($a->customer->age >= 25 && $a->customer->age <= 32){
                    $result['age']['25t32']['promoters'] += 1;
                }elseif($a->customer->age >= 33 && $a->customer->age <= 40){
                    $result['age']['33t40']['promoters'] += 1;
                }elseif($a->customer->age > 40){
                    $result['age']['40p']['promoters'] += 1;
                }
                
            }
            $result['totalcounts'] += 1;
        }
        $prdTbl = \Cake\ORM\TableRegistry::get('Products');
        $products = $prdTbl->find()->contain(['Purchases' =>function($q) use ($dateFrom,$dateTo){
            if($dateFrom){
                return $q->where([
                    'Purchases.sold_on >' => $dateFrom,
                    'Purchases.sold_on <=' => $dateTo,
                ]);
            }else{
                return $q;
            }  
        }])->hydrate(false)->where([
            'Products.store_id' => $this->_appSession->read('App.selectedStoreID')
        ])->all();
//        debug($products->toArray()); exit;
        $this->set('products',$products);
        $this->set('data', $result);
        $this->set('_serialize',['data']);
        
    }
    
    public function productsdata(){
//        $tbl = \Cake\ORM\TableRegistry::get('P')
    }


    public function test(){
        exit;
        $defaultQuestions = [
                        [
                          'store_id' => 2,
                          'question' => 'Where did you hear about us?',
                          'question_type' => 1,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 0,
                          'no_skip' => 0,
                          'options' => [
                                [ 'option' => 'Newspapers/Magazines' , 'status' => 1, 'soft_deleted' => 0 ],
                                [ 'option' => 'Banners/Hoardings' , 'status' => 1, 'soft_deleted' => 0 ],
                                [ 'option' => 'Zakoopi' , 'status' => 1, 'soft_deleted' => 0 ],
                                [ 'option' => 'Friends/Relatives' , 'status' => 1, 'soft_deleted' => 0 ],
                                [ 'option' => 'Never heard before' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                            
                        ],
                        [
                          'store_id' => 2,
                          'question' => 'How did you like our collection?',
                          'question_type' => 2,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 0,
                          'no_skip' => 0,
                          'options' => [
                                [ 'option' => '2' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                        ],
                        [
                          'store_id' => 2,
                          'question' => 'Are you happy with out price range?',
                          'question_type' => 2,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 0,
                          'no_skip' => 0,
                          'options' => [
                                [ 'option' => '2' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                        ],
                        [
                          'store_id' => 2,
                          'question' => 'How likely are you going to recommend our store to your friends / relatives?',
                          'question_type' => 2,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 1,
                          'no_skip' => 1,
                          'options' => [
                                [ 'option' => '3' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                        ],
                        [
                          'store_id' => 2,
                          'question' => 'Please give our store a rating out of 10',
                          'question_type' => 2,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 1,
                          'no_skip' => 0,
                          'options' => [
                                [ 'option' => '3' , 'status' => 1, 'soft_deleted' => 0 ]
                              ]
                        ], 
                        [
                          'store_id' => 2,
                          'question' => 'Please give your feedback on our store',
                          'question_type' => 3,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 1,
                          'no_skip' => 0
                        ],
                        [
                          'store_id' => 2,
                          'question' => 'Do you wish to leave any comments for us?',
                          'question_type' => 3,
                          'status' =>  1,
                          'soft_deleted' => 0,
                          'view_type' => '',
                          'no_delete' => 0,
                          'no_skip' => 0
                        ],
                    ];
        $tbl = \Cake\ORM\TableRegistry::get('Questions');
        $entities = $tbl->newEntities($defaultQuestions, ['associated' => ['Options']]);
        foreach ($entities as $entity) {
            // Save entity
            $tbl->save($entity);
        }

        debug($entities);
        exit;
    }
    
    
    public function publish(){
        $tblQues = \Cake\ORM\TableRegistry::get('Questions');
        $ques = $tblQues->find()->select(['id'])->where([
            'Questions.store_id' => $this->_appSession->read('App.selectedStoreID'),
            'Questions.view_type IN' => ['review','nps'],
        ])->all()->toArray();
        $qIds = [];
        foreach ($ques as $q){
            $qIds[] = $q->id;
        }
        $tblAns = \Cake\ORM\TableRegistry::get('Answers');
        $results = $tblAns->find()->contain([
            'Customers' => function($q){
                return $q->select(['id','name']);
            }
        ])->where([
            'Answers.question_id IN ' => $qIds
        ])->order(['Answers.id'=>'desc'])->all()->toArray();
//        debug($results);exit;
        $results = new \Cake\Collection\Collection($results);
        $data = $results->groupBy('created')->toArray();
        $this->set(compact("data"));
        $this->set('_serialize',['data']);
    }
    
    function posttozkp(){
        $this->request->allowMethod(['post','put']);
        $d = $this->request->data;
        $tblAns = \Cake\ORM\TableRegistry::get('Answers');
        $answers = $tblAns->find()->contain([
            'Customers' => function($q){
                return $q->select(['id','name','contact_no','email','gender']);
            },
            'Questions.Stores'
        ])->where([
            'Answers.id IN ' => $d
        ])->order(['Answers.id'=>'desc'])->all();
        $data = [
            "review" => "",
            "rating" => "",
            "store_slug" => "",
            "customer_phone" => "",
            "customer_email" => "",
            "customer_name" => ""
        ];
        
        foreach($answers as $answer){
            if($answer->answer_type == 3){
                $data["review"] = $answer->answer;
            }else{
                $data["rating"] = explode("/", $answer->answer);
                $data["rating"] = $data["rating"][0] / 2;
                $data["store_slug"] = $answer->question->store->slug;
                $data["customer_phone"] = $answer->customer->contact_no;
                $data["customer_email"] = $answer->customer->email;
                $data["customer_name"] = $answer->customer->name;
            }
        }
        $client = new \Cake\Network\Http\Client(['ssl_verify_peer'=>false]);
        $result = $client->post('http://www.zakoopi.com/api/Common/saveBusinessPublish.json', $data);
        $result = json_decode($result->body());
        if(@$result->data == 1){
            $tblAns->updateAll([
                'is_published' => 0
            ], [
                'customers_id IN ' => $answer->customer->id
            ]);
            $tblAns->updateAll([
                'is_published' => 1
            ], [
                'id IN ' => $d
            ]);
            
            $response = [
                'error' => 0,
                'msg' => 'Review published on www.zakoopi.com ...'
            ];
        }else{
            $response = [
                'error' => 1,
                'msg' => 'Something went wrong please try again'
            ];
        }
        $this->set(compact('response'));
        $this->set('_serialize',['response']);
    } 
    
}
