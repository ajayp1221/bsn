<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PushmessagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PushmessagesTable Test Case
 */
class PushmessagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PushmessagesTable
     */
    public $Pushmessages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.pushmessages',
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
        'app.customers',
        'app.customer_visits',
        'app.purchases',
        'app.products',
        'app.productcats',
        'app.sharedcode_redeemed',
        'app.welcomemsgs',
        'app.questions',
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
        $config = TableRegistry::exists('Pushmessages') ? [] : ['className' => 'App\Model\Table\PushmessagesTable'];
        $this->Pushmessages = TableRegistry::get('Pushmessages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Pushmessages);

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
