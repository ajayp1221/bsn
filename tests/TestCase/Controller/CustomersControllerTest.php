<?php
namespace App\Test\TestCase\Controller;

use App\Controller\CustomersController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\CustomersController Test Case
 */
class CustomersControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.customers',
        'app.stores',
        'app.brands',
        'app.clients',
        'app.devices',
        'app.sharedcodes',
        'app.sharedcode_redeemed',
        'app.social_connections',
        'app.socialshares',
        'app.smsplans',
        'app.clients_smsplans',
        'app.albums',
        'app.albumimages',
        'app.campaigns',
        'app.messages',
        'app.customer_visits',
        'app.productcats',
        'app.products',
        'app.purchases',
        'app.pushmessages',
        'app.questions',
        'app.answers',
        'app.options',
        'app.recommend_screen',
        'app.share_screen',
        'app.templatemessages',
        'app.welcomemsgs'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
