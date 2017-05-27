<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OptionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OptionsTable Test Case
 */
class OptionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OptionsTable
     */
    public $Options;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.options',
        'app.questions',
        'app.answers',
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
        'app.recommend_screen',
        'app.share_screen',
        'app.templatemessages',
        'app.welcomemsgs',
        'app.sharedcode_redeemed'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Options') ? [] : ['className' => 'App\Model\Table\OptionsTable'];
        $this->Options = TableRegistry::get('Options', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Options);

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
