<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $_appSession;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();
        $this->helpers = [
            'Shrink.Shrink' => array(
                'js' => array(
                    'path' => 'js/', // folder to find src js files
                    'cachePath' => 'js/', // folder to create cache files
                    'minifier' => 'jshrink'   // minifier to minify, false to leave as is
                ),
                'css' => array(
                    'path' => 'css/', // folder to find src css files
                    'cachePath' => 'css/', // folder to create cache files
                    'minifier' => 'cssmin', // minifier name to minify, false to leave as is
                    'charset' => 'utf-8'    // charset to use
                ),
                'url' => '', // url without ending /, incase you access from another domain
                'prefix' => 'bundle_', // prefix the beginning of cache files
                'debugLevel' => false
            )
        ];

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Cookie');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'loginAction' => [
                'controller' => 'Clients',
                'action' => '../#/login/signin'
            ],
            'logoutRedirect' => [
                'controller' => 'Clients',
                'action' => '../#/login/signin'
            ],
            'authError' => 'Unauthorized Access',
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ],
                    'userModel' => 'Clients'
                ]
            ]
        ]);
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        // setup App Session
        if ($this->_appSession == null) {
            $this->_appSession = $this->request->session();
        }

        //Temp Store ID setup for now
        if (!$this->_appSession->check('App.selectedStoreID')) {
            $this->_appSession->write('App.selectedStoreID', 6);
        }
        $this->set('AppSelectedStoreID', $this->_appSession->read('App.selectedStoreID'));
        //use $this->_appSession->read('App.selectedStoreID')
        $this->_appSession->write('App.selectedClienteID', $this->Auth->user('id'));
        $this->set('AppSelectedClientID', $this->_appSession->read('App.selectedClienteID'));
        //use $this->_appSession->read('App.selectedClienteID')



        $clientsTbl = \Cake\ORM\TableRegistry::get('Clients');
        $authUser = $clientsTbl->find()
                        ->select(['id', 'name', 'email', 'contact_no', 'image', 'address', 'sms_quantity', 'sms_sent', 'email_left', 'email_sent'])
                        ->hydrate(FALSE)->where(['id' => $this->Auth->user('id')])->first();
        $this->set("authUser", $authUser);

        $storeTbl = \Cake\ORM\TableRegistry::get('Stores');
        $str = $storeTbl->find()->where(['id' => $this->_appSession->read('App.selectedStoreID')])->first();
        $this->_appSession->write('App.selectedStoreSlug', $str->slug);
        $this->set('AppSelectedStoreSlug', $this->_appSession->read('App.selectedStoreSlug'));
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event) {
//        pr(php_uname('s')); exit;
        if (!array_key_exists('_serialize', $this->viewVars) &&
                in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

}
