<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * StoreImages Controller
 *
 * @property \App\Model\Table\StoreImagesTable $StoreImages
 */
class StoreImagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $storeImages = $this->StoreImages->find()
                ->where(['store_id' => $this->_appSession->read('App.selectedStoreID')])->toArray();
        if($storeImages){
            $result = [
                'error' => 0,
                'msg' => 'success',
                'v' => $storeImages
            ];
        }else{
            $result = [
                'error' => 1,
                'msg' => 'No data found'
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }

    /**
     * View method
     *
     * @param string|null $id Store Image id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $storeImage = $this->StoreImages->get($id, [
            'contain' => ['Stores']
        ]);

        $this->set('storeImage', $storeImage);
        $this->set('_serialize', ['storeImage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post','put']);
        $d = $this->request->data;
        $strZkpId = $this->StoreImages->Stores->find()->select(['zkpstoreid','slug'])
                ->where(['id'=>$this->_appSession->read('App.selectedStoreID')])->first();
        if($strZkpId){
            $dataToSend = $_POST;
            $dataToSend['all_images'] = [];
            foreach($this->request->data['all_images'] as $FileImg){
                $dataToSend['all_images'][] = fopen($FileImg['tmp_name'], 'r');
            }
            $httpClient = new \Cake\Network\Http\Client();
            $response = $httpClient->post("http://www.zakoopi.com/api/stores/StoreImagesAdd/add.json", $dataToSend , [
                'Host: business.zakoopi.com'
            ]);
        }
        foreach($this->request->data['all_images'] as $k => $str){
            $data['file_image'] = $str;
            $data['price'] = $d['price'];
            $data['store_id'] = $this->_appSession->read('App.selectedStoreID');
            $data['desc'] = $strZkpId->slug.time();
            $storeImage =  $this->StoreImages->newEntity();
            $storeImages = $this->StoreImages->patchEntity($storeImage, $data);
            $xx = $this->StoreImages->save($storeImages);     
        }
        if ($xx) {
            $result = [
                'error' => 0,
                'msg' => "Store image uploaded successfully"
            ];
        } else {
            $result = [
                'error' => 1,
                'msg' => "Something went wrong please try again"
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize', ['result']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Store Image id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $storeImage = $this->StoreImages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $storeImage = $this->StoreImages->patchEntity($storeImage, $this->request->data);
            if ($this->StoreImages->save($storeImage)) {
                $this->Flash->success(__('The store image has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The store image could not be saved. Please, try again.'));
            }
        }
        $stores = $this->StoreImages->Stores->find('list', ['limit' => 200]);
        $this->set(compact('storeImage', 'stores'));
        $this->set('_serialize', ['storeImage']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Store Image id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $storeImage = $this->StoreImages->get($id);
        if ($this->StoreImages->delete($storeImage)) {
            $result = [
                'error' => 0,
                'msg' => "Store image deleted successfully"
            ];
        } else {
            $result = [
                'error' => 1,
                'msg' => "Something went wrong please try again"
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
    }
}
