<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Albumimages Controller
 *
 * @property \App\Model\Table\AlbumimagesTable $Albumimages
 */
class AlbumimagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Albums']
        ];
        $albumimages = $this->paginate($this->Albumimages);

        $this->set(compact('albumimages'));
        $this->set('_serialize', ['albumimages']);
    }

    /**
     * View method
     *
     * @param string|null $id Albumimage id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $albumimage = $this->Albumimages->get($id, [
            'contain' => ['Albums']
        ]);

        $this->set('albumimage', $albumimage);
        $this->set('_serialize', ['albumimage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $albumimage = $this->Albumimages->newEntity();
        if ($this->request->is('post')) {
            $albumimage = $this->Albumimages->patchEntity($albumimage, $this->request->data);
            if ($this->Albumimages->save($albumimage)) {
                $this->Flash->success(__('The albumimage has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The albumimage could not be saved. Please, try again.'));
            }
        }
        $albums = $this->Albumimages->Albums->find('list', ['limit' => 200]);
        $this->set(compact('albumimage', 'albums'));
        $this->set('_serialize', ['albumimage']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Albumimage id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['put','post']);
        $albumimage = $this->Albumimages->get($id, [
            'contain' => []
        ]);
        $albumimage = $this->Albumimages->patchEntity($albumimage, $this->request->data['data']);
        if ($this->Albumimages->save($albumimage)) {
            $response['error'] = 0;
            $response['msg'] = "Update done successfully.";
        } else {
            $response['error'] = 1;
            $response['msg'] = "Something went wrong please try again";
        }
        $this->set(compact('response'));
        $this->set('_serialize', 'response');
    }

    /**
     * Delete method
     *
     * @param string|null $id Albumimage id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $albumimage = $this->Albumimages->get($id);
        $albumimage = $this->Albumimages->delete($albumimage);
        if ($albumimage) {
            $response['error'] = 0;
            $response['msg'] = "Image deleted successfully.";
        } else {
            $response['error'] = 1;
            $response['msg'] = "Something went wrong please try again";
        }
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}
