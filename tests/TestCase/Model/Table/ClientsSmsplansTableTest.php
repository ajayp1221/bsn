<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ClientsSmsplansTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ClientsSmsplansTable Test Case
 */
class ClientsSmsplansTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ClientsSmsplansTable
     */
    public $ClientsSmsplans;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.clients_smsplans',
        'app.clients',
        'app.brands',
        'app.stores',
        'app.albums',
        'app.albumimages',
        'app.campaigns',
        'app.messages',
        'app.customer_visits',
        'app.customers',
        'app.productcats',
        'app.products',
        'app.purchases',
        'app.pushmessages',
        'app.questions',
        'app.recommend_screen',
        'app.share_screen',
        'app.sharedcodes',
        'app.templatemessages',
        'app.welcomemsgs',
        'app.devices',
        'app.social_connections',
        'app.socialshares',
        'app.smsplans'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ClientsSmsplans') ? [] : ['className' => 'App\Model\Table\ClientsSmsplansTable'];
        $this->ClientsSmsplans = TableRegistry::get('ClientsSmsplans', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ClientsSmsplans);

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
