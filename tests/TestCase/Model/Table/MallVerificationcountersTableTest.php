<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MallVerificationcountersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MallVerificationcountersTable Test Case
 */
class MallVerificationcountersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MallVerificationcountersTable
     */
    public $MallVerificationcounters;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.mall_verificationcounters',
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
        'app.share_screen',
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
        $config = TableRegistry::exists('MallVerificationcounters') ? [] : ['className' => 'App\Model\Table\MallVerificationcountersTable'];
        $this->MallVerificationcounters = TableRegistry::get('MallVerificationcounters', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MallVerificationcounters);

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
