<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SmsplansTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SmsplansTable Test Case
 */
class SmsplansTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SmsplansTable
     */
    public $Smsplans;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.smsplans',
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
        'app.social_connections',
        'app.socialshares',
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
        $config = TableRegistry::exists('Smsplans') ? [] : ['className' => 'App\Model\Table\SmsplansTable'];
        $this->Smsplans = TableRegistry::get('Smsplans', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Smsplans);

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
