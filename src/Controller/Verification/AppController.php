<?php

namespace App\Controller\Verification;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie');
        $this->loadComponent('Auth', [
            
            'loginAction' => [
                'controller' => 'MallVerificationcounters',
                'action' => 'login'
            ],
            'logoutRedirect' => [
                'controller' => 'MallVerificationcounters',
                'action' => 'login'
            ],
            'authenticate' => [
                'Form' => [
                    'userModel' => 'MallVerificationcounters',
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Before Filter
     *
     * @param \Cake\Event\Event $event The beforeFilter event.
     * @return void
     */
    
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->loadModel('MallVerificationcounters');
        $authuser =  $this->MallVerificationcounters->find()->where(['id' => $this->Auth->user('id')])->first();
        $this->set('authuser', $authuser);
    }

        /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    
    
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }
}
