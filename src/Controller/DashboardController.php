<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Dashboard Controller
 * 
 * @property \Cake\Datasource\ConnectionInterface $conn Description
 */
class DashboardController extends AppController {

    public function getCstChart() {
        $conn = \Cake\Datasource\ConnectionManager::get('default');
        $query = "SELECT `id`, `customer_id`, from_unixtime(`came_at`), MONTH(from_unixtime(`came_at`)) as mnt, `visits_till_now`, `store_id` FROM `customer_visits` WHERE `store_id` = $st GROUP BY mnt";
        $conn->execute();

        $strId = $this->_appSession->read('App.selectedStoreID');
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow('r');
    }

    public function r($hash, $size = '100x0') {
        $size = explode("x", $size);
        $val = base64_decode($hash);
        $data = file_get_contents($val);
        $img = new \Imagick();
        if (!$data) {
            $img->newImage($size[0], $size[1], new \ImagickPixel('grey'));
            $img->setImageFormat('jpeg');
        } else {
            $img->readimageblob($data);
        }

        $img->scaleimage($size[0], $size[1]);
        $img->setformat('jpeg');
        $img->setcompression(90);

        $this->response->body($img->getimageblob());
        $this->response->type('jpg');
        // Optionally force file download
        $this->response->download(md5($hash) . '.jpg');
        // Return response object to prevent controller from trying to render
        // a view.
        return $this->response;
    }

    public function blockOne() {
        $cliTbl = \Cake\ORM\TableRegistry::get('Clients');
        $client = $cliTbl->find()->where([
                    'id' => $this->_appSession->read('App.selectedClienteID'),
                ])->first();
        $result = [
            'sms' => [
                'total' => $client->sms_quantity + $client->sms_sent,
                'sent' => $client->sms_sent
            ],
            'email' => [
                'total' => $client->email_left + $client->email_sent,
                'sent' => $client->email_sent
            ]
        ];
        $this->set("data", $result);
        $this->set('_serialize', ['data']);
    }

    public function blockTwo() {
        $cmpTbl = \Cake\ORM\TableRegistry::get('Campaigns');
        $campaings = $cmpTbl->find()->select([
                    'id',
                    'compaign_name',
                    'campaign_type',
                    'send_date',
                    'store_id'
                ])->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'send_date > ' => date("Y/m/d 00:00", strtotime("-2 weeks")),
            'campaign_type' => 'sms'
        ]);
        $cmp = [];
        foreach ($campaings as $c) {
            $cmp[] = $c->virtualProperties(['total_msg', 'total_delivered']);
        }

        $result = $cmp;
        $this->set("data", $result);
        $this->set('_serialize', ['data']);
    }

    public function blockThree() {
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
        $result = [
            'total_customers' => $cstTbl->find()->where([
                        'store_id' => $this->_appSession->read('App.selectedStoreID')
                    ])
                    ->count(),
            'opted_customers' => $cstTbl->find()->where([
                        'store_id' => $this->_appSession->read('App.selectedStoreID'),
                        'opt_in' => 1
                    ])
                    ->count()
        ];
        $this->set("data", $result);
        $this->set('_serialize', ['data']);
    }
    
    public function blockFour(){
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
        $results = $cstTbl->find()->select([
            'created_at' => "FROM_UNIXTIME(Customers.created)",
            'Customers.contact_no'
        ])->where([
            'store_id' => $this->_appSession->read('App.selectedStoreID'),
            'created > ' => strtotime("-14 days")
        ]);
        $results = new \Cake\Collection\Collection($results);
        $count = [];
        $results->each(function(&$item) use(&$count){
            $item->created_at = explode(" ", $item->created_at)[0];
            if(isset($count[$item->created_at])){
                $count[$item->created_at]++;
            }else{
                $count[$item->created_at] = 1;
            }
        });
        
        $cnt = 14;
        while($cnt != 0){
            $cnt--;
            $dt = date('Y-m-d',strtotime("-".$cnt." days"));
            if(!isset($count[$dt])){
                $count[$dt] = 0;
            }
        }
        ksort($count);
        $this->set("data", $count);
        $this->set("_serialize",['data']);
    }

}
