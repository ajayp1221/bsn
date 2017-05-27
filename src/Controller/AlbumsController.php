<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Albums Controller
 *
 * @property \App\Model\Table\AlbumsTable $Albums
 * @property \App\Model\Table\AlbumsharesTable $Albumshares
 */
class AlbumsController extends AppController
{
    
    
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['viewer']);
    }

    /**
     * Viewer Method
     * this urls is mapped from routes and will be shared to viewers
     */
    
    public function viewer($key = null,$cval = null){
        \Cake\Core\Configure::write('debug',TRUE);
        $this->viewBuilder()->layout('ajax');
        if(!$key || !$cval){
            throw new \Cake\Network\Exception\NotFoundException("Page not found 404!");
        }
        
        $cval = base64_decode($cval);
        $viewerType = filter_var($cval, FILTER_VALIDATE_EMAIL) ? 'email':'contact_no';
        
        $tblShr = \Cake\ORM\TableRegistry::get('Albumshares');
        $shrResults = $tblShr->find()->where([
//            'Albumshares.bucket_name' => $key,
            'Albumshares.status' => 1,
            'Albumshares.link LIKE' => '%'.$this->request->here(),
//            'Albumshares.'.$viewerType => $cval
        ])->first();
        if(!$shrResults){
            throw new \Cake\Network\Exception\NotFoundException("Page not found 404!");
        }
        if($shrResults->views >= $shrResults->maxviews){
            throw new \Cake\Network\Exception\NotFoundException("View Count exceeded the limit... contact provider");
        }
        $shrResults->views = $shrResults->views +1;
        $tblShr->save($shrResults);
        $ids = json_decode($shrResults->ids);
        $tblAlbumImg = \Cake\ORM\TableRegistry::get('Albumimages');
        $results = $tblAlbumImg->find()->contain([
            'Albums' => function($q){
                return $q->select(['id','name']);
            }
        ])->where([
            'Albumimages.id IN' => $ids
        ])->toArray();
        $data =  new \Cake\Collection\Collection($results);
        $data = $data->groupBy("album.name")->toArray();
        $this->set("data",$data);
    }
    
    /**
     * Add pictures to album with specific ID
     * 
     * @param type $id
     * @return type
     */
    public function addphotos($id = NULL){
        $album = $this->Albums->get($id, [
            'contain' => ['Albumimages']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $d = $this->request->data;
            foreach($d['albumimages'] as &$aImg){
                $aImg = [
                  "description" => "",
                  "status" => 1,
                  "file_image" => $aImg,
                ];
            }
            $album = $this->Albums->patchEntity($album, $d);
//            pr($album); exit;
            if ($this->Albums->save($album)) {
                $result = ['msg' => __('The album has been saved.'), 'error' => 0];
            } else {
                $result = ['msg' => __('The album could not be saved. Please, try again.'), 'error' => 1];
            }
        }
        $this->set('result',$result);
        $this->set('_serialize', ['result']);
    }
    
    

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Stores','Albumimages']
        ];
        $albums = $this->Albums->find()->contain(['Stores','Albumimages'])->where(['store_id' => $this->_appSession->read('App.selectedStoreID')]);
        $this->set('albums', $albums);
        $this->set('_serialize', ['albums']);
    }

    /**
     * View method
     *
     * @param string|null $id Album id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $album = $this->Albums->get($id, [
            'contain' => ['Stores', 'Albumimages']
        ]);

        $this->set('album', $album);
        $this->set('_serialize', ['album']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $album = $this->Albums->newEntity();
        if ($this->request->is('post')) {
            $result = [
                'error'  => 1,
                 'msg' => 'Something went wrong please try again'
            ];
            $this->request->data['status'] = 1;
            $this->request->data['store_id'] = $this->_appSession->read('App.selectedStoreID');
            $d = $this->request->data;
            foreach($d['albumimages'] as &$aImg){
                $aImg = [
                  "description" => "",
                  "status" => 1,
                  "file_image" => $aImg,
                ];
            }
            $album = $this->Albums->patchEntity($album, $d);
            if ($this->Albums->save($album)) {
                $result = [
                   'error'  => 0,
                    'msg' => 'Album has been created successfully'
                ];
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Album id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $album = $this->Albums->get($id, [
            'contain' => []
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $result = [
                   'error'  => 1,
                    'msg' => 'Something went wrong please try again'
                ];
            $d = $this->request->data;
            $d = json_decode($d['dt'],TRUE);
            unset($d['store']);
            unset($d['albumimages']);
            $album = $this->Albums->patchEntity($album, $d);
            if ($this->Albums->save($album)) {
                $result = [
                   'error'  => 0,
                    'msg' => 'Album has been created successfully'
                ];
            }
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Album id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $album = $this->Albums->get($id);
        if ($this->Albums->delete($album)) {
            $this->Flash->success(__('The album has been deleted.'));
        } else {
            $this->Flash->error(__('The album could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    public function share()
    {
        if($this->request->is(['post','put'])){
            $this->loadModel('Albumshares');
            $this->loadModel('Customers');
            $d = $this->request->data;
            $albumIds = json_encode($d['selectImgIds']);
            if(@$d['maxviews'] == ''){
                $d['maxviews'] = 5;
            }
            $bucketName = md5(time()."-ZKP:".rand(10,10));
            $link = "http://business.zakoopi.com/zKe/".$bucketName;
            if($d['shareVia']=='email'){
                $newmessage = $d['content'];
                if($d['who_send']=='1'){
                    $customeEmails = $this->Customers->find()
                            ->select(['email'])
                            ->where([
                                'store_id' => $this->_appSession->read('App.selectedStoreID'),
                                'email !=' => ''
                            ])
                            ->group(['email'])
                            ->hydrate(FALSE)->toArray();
                    foreach($customeEmails as $customeEmail){
                        $linkfinal = $link."/".base64_encode($customeEmail['email']);
                        $albumshares = "";
                        $albumshares = $this->Albumshares->newEntity();
                        $data = '';
                        $data = [
                            'email' => $customeEmail['email'],
                            'ids' => $albumIds,
                            'maxviews' => intval($d['maxviews']),
                            'expired' => $d['expire'],
                            'status' => 1,
                            'link' => $linkfinal,
                            'bucket_name' => $bucketName
                        ];
                        $albumshares = $this->Albumshares->patchEntity($albumshares, $data);
                        $saveAlbumshare = $this->Albumshares->save($albumshares);
                        if($saveAlbumshare){
                            $strUrl = file_get_contents('http://tinyurl.com/api-create.php?url=' . $linkfinal);
                            $methods = new \App\Common\Methods();
                            $apiResonpse = $methods->sendemail($customeEmail['email'],$newmessage." Link : ".$strUrl,$this->Auth->user('id'),$d['subject']);
                            $result = [
                                'error' => 0,
                                'msg' => 'Photos shared successfully'
                            ];
                        }else{
                            $result = [
                                'error' => 1,
                                'msg' => 'Sorry somthing went wrong please try again'
                            ];
                        }
                    }
                }else{
                    $customeEmails = explode(',',$d['receivers']);
                    foreach($customeEmails as $customeEmail){
                        $linkfinal = $link."/".base64_encode($customeEmail);
                        $albumshares = "";
                        $albumshares = $this->Albumshares->newEntity();
                        $data = '';
                        $data = [
                            'email' => $customeEmail,
                            'ids' => $albumIds,
                            'maxviews' => intval($d['maxviews']),
                            'expired' => $d['expire'],
                            'status' => 1,
                            'link' => $linkfinal,
                            'bucket_name' => $bucketName
                        ];
                        $albumshares = $this->Albumshares->patchEntity($albumshares, $data);
                        $result = $this->Albumshares->save($albumshares);
                        if($this->Albumshares->save($albumshares)){
                            $strUrl = file_get_contents('http://tinyurl.com/api-create.php?url=' . $linkfinal);
                            $methods = new \App\Common\Methods();
                            $apiResonpse = $methods->sendemail($customeEmail,$newmessage." Link : ".$strUrl,$this->Auth->user('id'),  $this->Auth->user('id'),$d['email']['subject']);
                            $result = [
                                'error' => 0,
                                'msg' => 'Photos shared successfully'
                            ];
                        }else{
                            $result = [
                                'error' => 1,
                                'msg' => 'Sorry somthing went wrong please try again'
                            ];
                        }
                    }
                }
            }elseif($d['shareVia']=='sms'){
                $newmessage = $d['sms_content'];
                if($d['whos_send']=='1'){
                    $customeContacts = $this->Customers->find()
                            ->select(['contact_no'])
                            ->where([
                                'store_id' => $this->_appSession->read('App.selectedStoreID'),
                                'contact_no !=' => ''
                            ])
                            ->group(['contact_no'])
                            ->hydrate(FALSE)->toArray();
                    foreach($customeContacts as $customeContact){
                        $linkfinal = $link."/".base64_encode($customeContact['contact_no']);
                        $albumshares = "";
                        $albumshares = $this->Albumshares->newEntity();
                        $data = '';
                        $data = [
                            'contact_no' => $customeContact['contact_no'],
                            'ids' => $albumIds,
                            'maxviews' => intval($d['maxviews']),
                            'expired' => $d['expire'],
                            'status' => 1,
                            'link' => $linkfinal,
                            'bucket_name' => $bucketName
                        ];
                        $albumshares = $this->Albumshares->patchEntity($albumshares, $data);
                        if($this->Albumshares->save($albumshares)){
                            $strUrl = file_get_contents('http://tinyurl.com/api-create.php?url=' . $linkfinal);
                            $methods = new \App\Common\Methods();
                            $apiResonpse = $methods->plivo($customeContact['contact_no'],$newmessage." Link : ".$strUrl,$this->Auth->user('id'),$this->Auth->user('senderid'));
                            $result = [
                                'error' => 0,
                                'msg' => 'Photos shared successfully'
                            ];
                        }else{
                            $result = [
                                'error' => 1,
                                'msg' => 'Sorry somthing went wrong please try again'
                            ];
                        }
                    }
                }else{
                    $customeContacts = explode(',',$d['contacts']);
                    foreach($customeContacts as $customeContact){
                        $linkfinal = $link."/".base64_encode($customeContact);
                        $albumshares = "";
                        $albumshares = $this->Albumshares->newEntity();
                        $data = '';
                        $data = [
                            'contact_no' => $customeContact,
                            'ids' => $albumIds,
                            'maxviews' => intval($d['maxviews']),
                            'expired' => $d['expire'],
                            'status' => 1,
                            'link' => $linkfinal,
                            'bucket_name' => $bucketName
                        ];
                        $albumshares = $this->Albumshares->patchEntity($albumshares, $data);
                        if($this->Albumshares->save($albumshares)){
                            $strUrl = file_get_contents('http://tinyurl.com/api-create.php?url=' . $linkfinal);
                            $methods = new \App\Common\Methods();
                            $apiResonpse = $methods->plivo($customeContact,$newmessage." Link : ".$strUrl,$this->Auth->user('id'),$this->Auth->user('senderid'));
                            $result = [
                                'error' => 0,
                                'msg' => 'Photos shared successfully'
                            ];
                        }else{
                            $result = [
                                'error' => 1,
                                'msg' => 'Sorry somthing went wrong please try again'
                            ];
                        }
                    }
                }
            }
        }
        $cstTbl = \Cake\ORM\TableRegistry::get('Customers');
        $cstCount = $cstTbl->find()->where([
            'Customers.store_id' => $this->_appSession->read('App.selectedStoreID'),
            'Customers.contact_no <>' => ''
        ])->count();
        $emCount = $cstTbl->find()->where([
            'Customers.store_id' => $this->_appSession->read('App.selectedStoreID'),
            'Customers.email <>' => ''
        ])->count();
        $this->set(compact(['cstCount','emCount','result']));
        $this->set('_serialize',['result']);
    }
}
