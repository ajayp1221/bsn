<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MallCustomerbillsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MallCustomerbillsTable Test Case
 */
class MallCustomerbillsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MallCustomerbillsTable
     */
    public $MallCustomerbills;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.mall_customerbills',
        'app.customers',
        'app.stores',
        'app.brands',
        'app.clients',
        'app.devices',
        'app.sharedcodes',
        'app.sharedcode_redeemed',
        'app.social_connections',
        'app.socialshares',
        'app.smsledger',
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
        'app.welcomemsgs',
        'app.customermeasurements'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MallCustomerbills') ? [] : ['className' => 'App\Model\Table\MallCustomerbillsTable'];
        $this->MallCustomerbills = TableRegistry::get('MallCustomerbills', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MallCustomerbills);

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
