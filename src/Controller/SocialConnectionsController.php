<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SocialConnections Controller
 *
 * @property \App\Model\Table\SocialConnectionsTable $SocialConnections
 */
class SocialConnectionsController extends AppController
{

    
    private $_fb_app_id = '201877090144591';
    private $_fb_app_secret = '4fa0058a1cdf0cff694b9e62f530901f';
    
    private $_tw_app_id = 'OFUAhLvVu8woKINv0rgIBJqPf';
    private $_tw_app_secret = 'zdoQXG6cDhMrzOIYof64MjyAnujzStBdv5lWe69z3Ir10iMD8Q';
    private $_tw_token_key = '1601258180-O7VN6c6jCQ4szODw6mf6SLXET0UvdkP53CLIGM1';
    private $_tw_token_secret = 'Kp7YNvtloWXMLalL0qPqEMqiOwBlGvLyaN0WC1vrvRGsb';
    
    
     /**
     * Connect with Facebook grab AccessToken
     */
    public function fbcb(){
        $fb = new \Facebook\Facebook([
            'app_id' => $this->_fb_app_id,
            'app_secret' => $this->_fb_app_secret,
            'default_graph_version' => 'v2.2',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        
        if($this->request->query('code')){
          try {  
            $accessToken = $helper->getAccessToken();  
          } catch(\Facebook\Exceptions\FacebookResponseException $e) {  
            // When Graph returns an error  
            echo 'Graph returned an error: ' . $e->getMessage();  
            exit;  
          } catch(\Facebook\Exceptions\FacebookSDKException $e) {  
            // When validation fails or other local issues  
            echo 'Facebook SDK returned an error: ' . $e->getMessage();  
            exit;  
          }  

          if (isset($accessToken)) {  
              $accessTokenVal = $accessToken->getValue();
              $oAuth2Client = $fb->getOAuth2Client();  
              $tokenMetadata = $oAuth2Client->debugToken($accessToken); 
              $tokenMetadata->validateExpiration();   
              if (! $accessToken->isLongLived()) {  
                // Exchanges a short-lived access token for a long-lived one  
                try {  
                  $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);  
                } catch (\Facebook\Exceptions\FacebookSDKException $e) {  
                  echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>";  
                  exit;  
                } 
                echo '<h3>Long-lived</h3>';  
                var_dump($accessToken->getValue());  
              }
              $fb_access_token = (string)$accessToken;
              
              $fbconnection = $this->SocialConnections->find()->where([
                  'model' => 'facebook',
                  'client_id' => $this->_appSession->read('App.selectedClienteID')
              ])->first();
              if(!$fbconnection){
                  $fbconnection = $this->SocialConnections->newEntity([
                    'model' => 'facebook',
                    'client_id' => $this->_appSession->read('App.selectedClienteID'),
                    'access_token' => $fb_access_token,
                    'raw_data' => serialize($tokenMetadata),
                    'status' => 1
                  ]);
              }else{
                  $fbconnection->access_token = $fb_access_token;
                  $fbconnection->raw_data = serialize($tokenMetadata);
              }
              $this->SocialConnections->save($fbconnection);
              
//              echo $fb_access_token;
              $this->redirect('/#/socialshare/create');
          }  

        }else{
            $permissions = ['email', 'publish_actions','publish_pages','manage_pages']; // Optional permissions
            $this->redirect($helper->getLoginUrl('http://bnew.zakoopi.com/SocialConnections/fbcb', $permissions));
        }
    }
    
    /**
     * Connect with Twitter grab AccessToken
     * 
     */
    public function twcb(){
        $session = $this->request->session();
        $consumerKey = $this->_tw_app_id;
        $consumerSecret = $this->_tw_app_secret;
        $oauthToken = $this->_tw_token_key;
        $oauthTokenSecret = $this->_tw_token_secret;
//        $twitter = new \Abraham\TwitterOAuth\TwitterOAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
        if($this->request->query('oauth_token')){
            $twitter = new \Abraham\TwitterOAuth\TwitterOAuth($consumerKey, $consumerSecret, $session->read('Tw.oauthToken'),  $session->read('Tw.oauthSecret'));
            $result = $twitter->oauth('oauth/access_token',[
              'oauth_verifier'  => $this->request->query('oauth_verifier')
            ]);
            
             $twconnection = $this->SocialConnections->find()->where([
                  'model' => 'twitter',
                  'client_id' => $this->_appSession->read('App.selectedClienteID')
              ])->first();
              if(!$twconnection){
                  $twconnection = $this->SocialConnections->newEntity([
                    'model' => 'twitter',
                    'client_id' => $this->_appSession->read('App.selectedClienteID'),
                    'access_token' => json_encode(['oauth_token'=>$result['oauth_token'],'oauth_token_secret'=>$result['oauth_token_secret']]),
                    'raw_data' => serialize($result),
                    'status' => 1
                  ]);
              }else{
                  $twconnection->access_token = json_encode(['oauth_token'=>$result['oauth_token'],'oauth_token_secret'=>$result['oauth_token_secret']]);
                  $twconnection->raw_data = serialize($result);
              }
              $this->SocialConnections->save($twconnection);
              $this->redirect('/#/socialshare/create');
        }else{
            $twitter = new \Abraham\TwitterOAuth\TwitterOAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
            $result = $twitter->oauth('oauth/request_token',[
              'oauth_callback'  => 'http://business.zakoopi.com/SocialConnections/twcb'
            ]);
            $session->write('Tw.oauthToken', $result['oauth_token']);
            $session->write('Tw.oauthSecret', $result['oauth_token_secret']);
            $this->redirect('https://api.twitter.com/oauth/authorize?oauth_token='.$result['oauth_token']);
        }
        
    }

    
    
    
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Clients']
        ];
        $socialConnections = $this->paginate($this->SocialConnections);

        $this->set(compact('socialConnections'));
        $this->set('_serialize', ['socialConnections']);
    }

    /**
     * View method
     *
     * @param string|null $id Social Connection id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $socialConnection = $this->SocialConnections->get($id, [
            'contain' => ['Clients']
        ]);

        $this->set('socialConnection', $socialConnection);
        $this->set('_serialize', ['socialConnection']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $socialConnection = $this->SocialConnections->newEntity();
        if ($this->request->is('post')) {
            $socialConnection = $this->SocialConnections->patchEntity($socialConnection, $this->request->data);
            if ($this->SocialConnections->save($socialConnection)) {
                $this->Flash->success(__('The social connection has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The social connection could not be saved. Please, try again.'));
            }
        }
        $clients = $this->SocialConnections->Clients->find('list', ['limit' => 200]);
        $this->set(compact('socialConnection', 'clients'));
        $this->set('_serialize', ['socialConnection']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Social Connection id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $socialConnection = $this->SocialConnections->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $socialConnection = $this->SocialConnections->patchEntity($socialConnection, $this->request->data);
            if ($this->SocialConnections->save($socialConnection)) {
                $this->Flash->success(__('The social connection has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The social connection could not be saved. Please, try again.'));
            }
        }
        $clients = $this->SocialConnections->Clients->find('list', ['limit' => 200]);
        $this->set(compact('socialConnection', 'clients'));
        $this->set('_serialize', ['socialConnection']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Social Connection id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($type = null)
    {
        $this->request->allowMethod(['delete']);
        $socialConnection = $this->SocialConnections->find()->where([
            'SocialConnections.client_id' => $this->_appSession->read('App.selectedClienteID'),
            'SocialConnections.model' => $type
        ])->first();
        if ($this->SocialConnections->delete($socialConnection)) {
            $result = [
              'error' => 0,
              'msg' => __('The social connection has been deleted.')
            ];
            $this->Flash->success(__('The social connection has been deleted.'));
        } else {
            $result = [
              'error' => 0,
              'msg' => __('The social connection could not be deleted. Please, try again.')
            ];
        }
        $this->set('result',$result);
        $this->set('_serialize',['result']);
    }
}
