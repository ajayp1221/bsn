<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DevicesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DevicesTable Test Case
 */
class DevicesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DevicesTable
     */
    public $Devices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.devices',
        'app.clients',
        'app.brands',
        'app.stores',
        'app.albums',
        'app.albumimages',
        'app.campaigns',
        'app.messages',
        'app.customer_visits',
        'app.customers',
        'app.purchases',
        'app.sharedcode_redeemed',
        'app.sharedcodes',
        'app.welcomemsgs',
        'app.productcats',
        'app.products',
        'app.pushmessages',
        'app.questions',
        'app.recommend_screen',
        'app.share_screen',
        'app.templatemessages',
        'app.social_connections',
        'app.socialshares',
        'app.smsplans',
        'app.clients_smsplans'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Devices') ? [] : ['className' => 'App\Model\Table\DevicesTable'];
        $this->Devices = TableRegistry::get('Devices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Devices);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
