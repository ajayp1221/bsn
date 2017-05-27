<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CampaignsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CampaignsTable Test Case
 */
class CampaignsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CampaignsTable
     */
    public $Campaigns;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.campaigns',
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
        'app.customer_visits',
        'app.customers',
        'app.messages',
        'app.productcats',
        'app.products',
        'app.purchases',
        'app.pushmessages',
        'app.questions',
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
        $config = TableRegistry::exists('Campaigns') ? [] : ['className' => 'App\Model\Table\CampaignsTable'];
        $this->Campaigns = TableRegistry::get('Campaigns', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Campaigns);

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
