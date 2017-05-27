<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShareScreenTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShareScreenTable Test Case
 */
class ShareScreenTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ShareScreenTable
     */
    public $ShareScreen;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.share_screen',
        'app.stores',
        'app.brands',
        'app.clients',
        'app.devices',
        'app.sharedcodes',
        'app.customers',
        'app.answers',
        'app.questions',
        'app.options',
        'app.customer_visits',
        'app.smsledger',
        'app.messages',
        'app.campaigns',
        'app.purchases',
        'app.products',
        'app.productcats',
        'app.sharedcode_redeemed',
        'app.welcomemsgs',
        'app.customermeasurements',
        'app.social_connections',
        'app.socialshares',
        'app.smsplans',
        'app.clients_smsplans',
        'app.albums',
        'app.albumimages',
        'app.pushmessages',
        'app.recommend_screen',
        'app.templatemessages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ShareScreen') ? [] : ['className' => 'App\Model\Table\ShareScreenTable'];
        $this->ShareScreen = TableRegistry::get('ShareScreen', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ShareScreen);

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
