<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CustomerVisits Controller
 *
 * @property \App\Model\Table\CustomerVisitsTable $CustomerVisits
 */
class CustomerVisitsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        if($range = json_decode($this->request->query('range'))){
            $dateFrom = strtotime($range->start,0);
            $dateTo = strtotime($range->end,0) + 24*60*60;
        }else{
            $dateFrom = false;
            $dateTo = false;
        }
        if($dateFrom){
            $cstRecords = $this->CustomerVisits->find()->where([
                'CustomerVisits.store_id' => $this->_appSession->read('App.selectedStoreID'),
                'CustomerVisits.came_at > '=> $dateFrom,
                'CustomerVisits.came_at <= '=> $dateTo,
            ])->order(['CustomerVisits.came_at' => 'asc'])->all();
        }else{
            $cstRecords = $this->CustomerVisits->find()->where([
                'CustomerVisits.store_id' => $this->_appSession->read('App.selectedStoreID')
            ])->order(['CustomerVisits.came_at' => 'asc'])->all();
        }
        $cstCount = [];
        $finalCount = [
            'repeat' => [],
            'first' => [],
            'totalRepeat' => 0,
            'totalFirst' => 0,
            'countRepeat' => 0,
            'countFirst' => 0
        ];
        foreach($cstRecords as $c){
            if(!isset($cstCount[date('d-m', $c->came_at)])){
                $cstCount[date('d-m', $c->came_at)] = [
                    'repeat' => [],
                    'first' => [],
                    'totalRepeat' => 0,
                    'totalFirst' => 0,
                    'countRepeat' => 0,
                    'countFirst' => 0,
                    'countTotal' => 0
                ];
            }
            if($c->visits_till_now > 1 ){
                if(!in_array($c->customer_id, $cstCount[date('d-m', $c->came_at)]['repeat'])){
                    $cstCount[date('d-m', $c->came_at)]['repeat'][] = $c->customer_id;
                    $cstCount[date('d-m', $c->came_at)]['totalRepeat'] += 1;
                    $finalCount['totalRepeat'] += 1;
                }     
            }else{
                if(!in_array($c->customer_id, $cstCount[date('d-m', $c->came_at)]['first'])){
                    $cstCount[date('d-m', $c->came_at)]['first'][] = $c->customer_id;
                    $cstCount[date('d-m', $c->came_at)]['totalFirst'] += 1;
                    $finalCount['totalFirst'] += 1;
                }
            }
        }
        $finalCount['countRepeat'] = $finalCount['totalRepeat'];
        $finalCount['countFirst'] = $finalCount['totalFirst'];
        $t = $finalCount['totalRepeat'] + $finalCount['totalFirst'];
        $t = $t == 0 ? 1 : $t;
        $finalCount['totalRepeat'] = $finalCount['totalRepeat'] / $t * 100;
        $finalCount['totalFirst'] = $finalCount['totalFirst'] / $t * 100;
        foreach ($cstCount as &$s){
            $s['countRepeat'] = $s['totalRepeat'];
            $s['countFirst'] = $s['totalFirst'];
            $s['countTotal'] = $s['totalRepeat'] + $s['totalFirst'];
            $t = $s['totalRepeat'] + $s['totalFirst'];
            $t = $t == 0 ? 1 : $t;
            $s['totalRepeat'] = $s['totalRepeat'] / $t * 100;
            $s['totalFirst'] = $s['totalFirst'] / $t * 100;
        }
//        debug($cstCount); exit;
        $this->set('customerVisits', $cstCount);
        $this->set('finalCount', $finalCount);
        $this->set('_serialize', ['customerVisits','finalCount']);
    }

    
    public function topcustomers(){
        $store_id = $this->_appSession->read('App.selectedStoreID');
        $query = "SELECT v.id,customer_id, count(*) as visit_count , c.name, c.email, FROM_UNIXTIME(v.came_at) as came_at , c.contact_no FROM `customer_visits` as v LEFT JOIN customers as c ON (v.customer_id = c.id) WHERE v.`store_id` = $store_id GROUP BY v.customer_id ORDER BY v.id DESC";
        $conn = \Cake\Datasource\ConnectionManager::get('default');
        $data = $conn->query($query)->fetchAll('assoc');
        $this->set('result',$data);
        $this->set('_serialize',['result']);        
    }
    public function exportTopCustomers() {
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', -1);
        \Cake\Core\Configure::write('debug', true); 
        $store_id = $this->_appSession->read('App.selectedStoreID');
        $query = "SELECT v.id,customer_id, count(*) as visit_count , c.name, c.email, FROM_UNIXTIME(v.came_at) as came_at , c.contact_no FROM `customer_visits` as v LEFT JOIN customers as c ON (v.customer_id = c.id) WHERE v.`store_id` = $store_id GROUP BY v.customer_id ORDER BY v.id DESC";
        $conn = \Cake\Datasource\ConnectionManager::get('default');
        $data = $conn->query($query)->fetchAll('assoc');
            $heads = [
                "Customer Name","Mobile No.","Total Visits Till Now","Email", "Last Vist On","Customers DB_ID"
            ];
            
            $dataSet[] = $heads;
            foreach ($data as $av){
                $data = [];
                $data[0] = $av['name'];
                $data[1] = $av['contact_no'];
                $data[2] = $av['visit_count'];
                $data[3] = $av['email'];
                $data[4] = explode(" ", $av['came_at'])[0];
                $data[5] = $av['id'];
                
                $dataSet[] = $data;
            }
            $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;
            \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, []);
            $excel2 = \PHPExcel_IOFactory::createReader('Excel2007');
            $excel2 = $excel2->load(WWW_ROOT . 'data' . DS . 'exportTopCustomer.xlsx'); // Empty Sheet
            $objWorksheet = $excel2->setActiveSheetIndex(0);
            foreach ($dataSet as $ik => $i){
                foreach ($i as $jk => $j){
                    $objWorksheet->setCellValueByColumnAndRow($jk, $ik+1, $j);
                }
            }
            $objWriter = \PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
            ob_start();
            $objWriter->save('php://output');
            $this->response->body(ob_get_clean());
            $this->response->type('xlsx');
            $this->response->download('ZKP-TOPCUSTOMERS-'.date('d-m-Y').'.xlsx');
            return $this->response;
    }




    /**
     * View method
     *
     * @param string|null $id Customer Visit id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customerVisit = $this->CustomerVisits->get($id, [
            'contain' => ['Customers', 'Stores']
        ]);
        $this->set('customerVisit', $customerVisit);
        $this->set('_serialize', ['customerVisit']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customerVisit = $this->CustomerVisits->newEntity();
        if ($this->request->is('post')) {
            $customerVisit = $this->CustomerVisits->patchEntity($customerVisit, $this->request->data);
            if ($this->CustomerVisits->save($customerVisit)) {
                $this->Flash->success(__('The customer visit has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The customer visit could not be saved. Please, try again.'));
            }
        }
        $customers = $this->CustomerVisits->Customers->find('list', ['limit' => 200]);
        $stores = $this->CustomerVisits->Stores->find('list', ['limit' => 200]);
        $this->set(compact('customerVisit', 'customers', 'stores'));
        $this->set('_serialize', ['customerVisit']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer Visit id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $customerVisit = $this->CustomerVisits->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customerVisit = $this->CustomerVisits->patchEntity($customerVisit, $this->request->data);
            if ($this->CustomerVisits->save($customerVisit)) {
                $this->Flash->success(__('The customer visit has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The customer visit could not be saved. Please, try again.'));
            }
        }
        $customers = $this->CustomerVisits->Customers->find('list', ['limit' => 200]);
        $stores = $this->CustomerVisits->Stores->find('list', ['limit' => 200]);
        $this->set(compact('customerVisit', 'customers', 'stores'));
        $this->set('_serialize', ['customerVisit']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer Visit id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customerVisit = $this->CustomerVisits->get($id);
        if ($this->CustomerVisits->delete($customerVisit)) {
            $this->Flash->success(__('The customer visit has been deleted.'));
        } else {
            $this->Flash->error(__('The customer visit could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
