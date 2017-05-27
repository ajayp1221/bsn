<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Gshuplogs Controller
 *
 * @property \App\Model\Table\GshuplogsTable $Gshuplogs
 */
class GshuplogsController extends AppController
{
    
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['optoutkw']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Externals']
        ];
        $this->set('gshuplogs', $this->paginate($this->Gshuplogs));
        $this->set('_serialize', ['gshuplogs']);
    }

    /**
     * View method
     *
     * @param string|null $id Gshuplog id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $gshuplog = $this->Gshuplogs->get($id, [
            'contain' => ['Externals']
        ]);
        $this->set('gshuplog', $gshuplog);
        $this->set('_serialize', ['gshuplog']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $gshuplog = $this->Gshuplogs->newEntity();
        if ($this->request->is('post')) {
            $gshuplog = $this->Gshuplogs->patchEntity($gshuplog, $this->request->data);
            if ($this->Gshuplogs->save($gshuplog)) {
                $this->Flash->success(__('The gshuplog has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The gshuplog could not be saved. Please, try again.'));
            }
        }
        $externals = $this->Gshuplogs->Externals->find('list', ['limit' => 200]);
        $this->set(compact('gshuplog', 'externals'));
        $this->set('_serialize', ['gshuplog']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Gshuplog id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $gshuplog = $this->Gshuplogs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $gshuplog = $this->Gshuplogs->patchEntity($gshuplog, $this->request->data);
            if ($this->Gshuplogs->save($gshuplog)) {
                $this->Flash->success(__('The gshuplog has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The gshuplog could not be saved. Please, try again.'));
            }
        }
        $externals = $this->Gshuplogs->Externals->find('list', ['limit' => 200]);
        $this->set(compact('gshuplog', 'externals'));
        $this->set('_serialize', ['gshuplog']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Gshuplog id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $gshuplog = $this->Gshuplogs->get($id);
        if ($this->Gshuplogs->delete($gshuplog)) {
            $this->Flash->success(__('The gshuplog has been deleted.'));
        } else {
            $this->Flash->error(__('The gshuplog could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Unsubscribe From Business.Zakoopi.com
     */
    public function optoutkw(){
        $number = $this->request->query('msisdn');
        $number = ltrim($number, "91");
        $keyword = $this->request->query('keyword');
        
//        $strTbl = \Cake\ORM\TableRegistry::get('Stores');
//        $store = $strTbl->find()->contain(['Brands'])->where([
//            'Stores.unsub_gshup_keyword' => $keyword
//        ])->first();
        
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
//        $cst = $cstTbl->find()->where([
//            "store_id" => $store->id
//        ])->first();
        
//        if($cst){
            $cstTbl->updateAll([
                "opt_in" => 0,
                "status" => 0
            ], [
//                "store_id" => $store->id,
                "contact_no" => $number
            ]);
            $method = new \App\Common\Methods();
            if($num = $methods->checkNum($number)){
//                $method->smsgshup($num, "You have been unsubscribed successfully.", $store->brand->client_id, "ZAKOPI", "Responses to unsubscribe requests", $cst->id, $store->id);
                $method->smsgshup($num, "You have been unsubscribed successfully.", null, "ZAKOPI", "Responses to unsubscribe requests");
            }
//        }
        
//        $data = [];
//        $pth = WWW_ROOT."tmp".DS."opt.txt";
//        $data['query'] = $this->request->query;
//        $data['odata'] = $this->request->data;
//	$data['store'] = $store->id;
//        if (is_file($pth)){
//            file_put_contents($pth, print_r($data,true).PHP_EOL , FILE_APPEND);
//        }else{
//            file_put_contents($pth, print_r($data,true));
//        }
        exit;
        
    }
    
}
