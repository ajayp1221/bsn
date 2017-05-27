<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Smsledger Controller
 *
 * @property \App\Model\Table\SmsledgerTable $Smsledger
 */
class SmsledgerController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $smsledger = $this->Smsledger->find()->contain([
            'Stores' => function($q){
                return $q->select(['id','name','slug']);
            },
            'Customers' => function($q){
                return $q->select([
                    'id','store_id','name','email','contact_no','gender','dob','doa'
                ]);
            },
            'Clients' => function($q){
                return $q->select([
                    'id','name','email','contact_no','sms_quantity','sms_sent','email_left','email_sent'
                ]);
            }
        ])->where([
            'Smsledger.client_id' => $this->Auth->user('id')
        ]);
        $limit = 10;
        if(@$this->request->query['limit']){
            $limit = $this->request->query['limit'];
        }
        $this->paginate = [
            'order' => [
                'Smsledger.id' => 'desc'
            ],
            'limit' => $limit,
        ];
        $count = $smsledger->count();
        $smsledger =  $this->paginate($smsledger);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        $this->set(compact('smsledger','pager'));
        $this->set('_serialize', ['smsledger','pager']);
    }
    
    
    public function getCredits(){
        $smsledger = $this->Smsledger->find()->contain([
            'Clients' => function($q){
                return $q->select([
                    'id','name','email','contact_no','sms_quantity','sms_sent','email_left','email_sent'
                ]);
            }
        ])->where([
            'Smsledger.client_id' => $this->Auth->user('id'),
            'Smsledger.credit > ' => 0
        ]);
        $limit = 10;
        if(@$this->request->query['limit']){
            $limit = $this->request->query['limit'];
        }
        $this->paginate = [
            'order' => [
                'Smsledger.id' => 'desc'
            ],
            'limit' => $limit,
        ];
        $count = $smsledger->count();
        $smsledger =  $this->paginate($smsledger);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        $this->set(compact('smsledger','pager'));
        $this->set('_serialize', ['smsledger','pager']);
    }
    
    public function queryDebits(){
        $comments = $this->request->data['comments'] == '' ? '%' : $this->request->data['comments'];
        $smsledger = $this->Smsledger->find()->contain([
            'Clients' => function($q){
                return $q->select([
                    'id','name','email','contact_no','sms_quantity','sms_sent','email_left','email_sent'
                ]);
            },
            'Stores' => function($q){
                return $q->select([
                    'id','name'
                ]);
            },
            'Customers' => function($q){
                return $q->select([
                    'id','contact_no','name'
                ]);
            }
        ])->where([
            'Smsledger.client_id' => $this->Auth->user('id'),
            'Smsledger.comments LIKE' => $comments
        ]);
        $limit = 10;
        if(@$this->request->query['limit']){
            $limit = $this->request->query['limit'];
        }
        $this->paginate = [
            'order' => [
                'Smsledger.id' => 'desc'
            ],
            'limit' => $limit,
        ];
        $count = $smsledger->count();
        $smsledger =  $this->paginate($smsledger);
        $pObj = new \Cake\View\Helper\PaginatorHelper(new \Cake\View\View());
        $pager['totalRec'] = $count;
        $pager['step'] = $limit;
        $pager['currentPg'] = $pObj->current();
        $pager['totalPg'] = ceil($pager['totalRec'] / $pager['step']);
        $this->set(compact('smsledger','pager'));
        $this->set('_serialize', ['smsledger','pager']);
    }

    public function getGshupDetails(){
        $d = $this->request->data;
        $gsupId = explode("|", $d['extra'])[0];
        $gsTbl = \Cake\ORM\TableRegistry::get('Gshuplogs');
        $rec = $gsTbl->get($gsupId);
        if($rec){
            $res = $rec['status'] == 'success' ? 'Pending Response' : $rec['status'];
            $res .= '<br>'.$rec['extras'];
        }else{
            $res = 'Details Not Found';
        }
        echo $res;
        exit;
    }
}
