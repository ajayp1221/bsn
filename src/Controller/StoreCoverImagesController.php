<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * StoreCoverImages Controller
 *
 * @property \App\Model\Table\StoreCoverImagesTable $StoreCoverImages
 */
class StoreCoverImagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $storeCoverImages = $this->StoreCoverImages->find()
                ->where(['store_id' => $this->_appSession->read('App.selectedStoreID')])->toArray();
        if($storeCoverImages){
            $result = [
                'error' => 0,
                'msg' => 'success',
                'v' => $storeCoverImages
            ];
        }else{
            $result = [
                'error' => 1,
                'msg' => 'No data found'
            ];
        }
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
    }

    /**
     * View method
     *
     * @param string|null $id Store Cover Image id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $storeCoverImage = $this->StoreCoverImages->get($id, [
            'contain' => ['Stores']
        ]);

        $this->set('storeCoverImage', $storeCoverImage);
        $this->set('_serialize', ['storeCoverImage']);
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
        $strZkpId = $this->StoreCoverImages->Stores->find()->select(['zkpstoreid','slug'])
                ->where(['id'=>$this->_appSession->read('App.selectedStoreID')])->first();
        if($strZkpId){
            $dataToSend = $_POST;
            $dataToSend['all_images'] = [];
            foreach($this->request->data['all_images'] as $FileImg){
                $dataToSend['all_images'][] = fopen($FileImg['tmp_name'], 'r');
            }
            $httpClient = new \Cake\Network\Http\Client();
            $response = $httpClient->post("http://www.zakoopi.com/api/StoreCoverImages/add.json", $dataToSend , [
                'Host: business.zakoopi.com'
            ]);
        }
        foreach($this->request->data['all_images'] as $k => $str){
            $data['file_image'] = $str;
            $data['store_id'] = $this->_appSession->read('App.selectedStoreID');
            $storeImage =  $this->StoreCoverImages->newEntity();
            $storeImages = $this->StoreCoverImages->patchEntity($storeImage, $data);
            $xx = $this->StoreCoverImages->save($storeImages);     
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
     * @param string|null $id Store Cover Image id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $storeCoverImage = $this->StoreCoverImages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $storeCoverImage = $this->StoreCoverImages->patchEntity($storeCoverImage, $this->request->data);
            if ($this->StoreCoverImages->save($storeCoverImage)) {
                $this->Flash->success(__('The store cover image has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The store cover image could not be saved. Please, try again.'));
            }
        }
        $stores = $this->StoreCoverImages->Stores->find('list', ['limit' => 200]);
        $this->set(compact('storeCoverImage', 'stores'));
        $this->set('_serialize', ['storeCoverImage']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Store Cover Image id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $storeCoverImage = $this->StoreCoverImages->get($id);
        if ($this->StoreCoverImages->delete($storeCoverImage)) {
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
