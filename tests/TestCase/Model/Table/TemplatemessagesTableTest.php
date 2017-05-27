<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TemplatemessagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TemplatemessagesTable Test Case
 */
class TemplatemessagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TemplatemessagesTable
     */
    public $Templatemessages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.templatemessages',
        'app.stores',
        'app.brands',
        'app.clients',
        'app.devices',
        'app.sharedcodes',
        'app.customers',
        'app.customer_visits',
        'app.messages',
        'app.campaigns',
        'app.purchases',
        'app.products',
        'app.productcats',
        'app.sharedcode_redeemed',
        'app.welcomemsgs',
        'app.social_connections',
        'app.socialshares',
        'app.smsplans',
        'app.clients_smsplans',
        'app.albums',
        'app.albumimages',
        'app.pushmessages',
        'app.questions',
        'app.answers',
        'app.options',
        'app.recommend_screen',
        'app.share_screen'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Templatemessages') ? [] : ['className' => 'App\Model\Table\TemplatemessagesTable'];
        $this->Templatemessages = TableRegistry::get('Templatemessages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Templatemessages);

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
