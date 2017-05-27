<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SocialConnectionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SocialConnectionsTable Test Case
 */
class SocialConnectionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SocialConnectionsTable
     */
    public $SocialConnections;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.social_connections',
        'app.clients',
        'app.brands',
        'app.stores',
        'app.albums',
        'app.albumimages',
        'app.campaigns',
        'app.messages',
        'app.customers',
        'app.customer_visits',
        'app.purchases',
        'app.products',
        'app.productcats',
        'app.sharedcode_redeemed',
        'app.sharedcodes',
        'app.welcomemsgs',
        'app.pushmessages',
        'app.questions',
        'app.answers',
        'app.options',
        'app.recommend_screen',
        'app.share_screen',
        'app.templatemessages',
        'app.devices',
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
        $config = TableRegistry::exists('SocialConnections') ? [] : ['className' => 'App\Model\Table\SocialConnectionsTable'];
        $this->SocialConnections = TableRegistry::get('SocialConnections', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SocialConnections);

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
