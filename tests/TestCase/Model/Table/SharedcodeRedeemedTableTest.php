<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SharedcodeRedeemedTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SharedcodeRedeemedTable Test Case
 */
class SharedcodeRedeemedTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SharedcodeRedeemedTable
     */
    public $SharedcodeRedeemed;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sharedcode_redeemed',
        'app.customers',
        'app.stores',
        'app.brands',
        'app.clients',
        'app.devices',
        'app.sharedcodes',
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
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SharedcodeRedeemed') ? [] : ['className' => 'App\Model\Table\SharedcodeRedeemedTable'];
        $this->SharedcodeRedeemed = TableRegistry::get('SharedcodeRedeemed', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SharedcodeRedeemed);

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
