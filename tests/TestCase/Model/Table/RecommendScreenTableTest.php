<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RecommendScreenTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RecommendScreenTable Test Case
 */
class RecommendScreenTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RecommendScreenTable
     */
    public $RecommendScreen;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.recommend_screen',
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
        'app.pushmessages',
        'app.questions',
        'app.answers',
        'app.options',
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
        $config = TableRegistry::exists('RecommendScreen') ? [] : ['className' => 'App\Model\Table\RecommendScreenTable'];
        $this->RecommendScreen = TableRegistry::get('RecommendScreen', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RecommendScreen);

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
