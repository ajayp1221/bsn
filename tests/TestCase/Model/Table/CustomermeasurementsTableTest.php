<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomermeasurementsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomermeasurementsTable Test Case
 */
class CustomermeasurementsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomermeasurementsTable
     */
    public $Customermeasurements;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.customermeasurements',
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
        $config = TableRegistry::exists('Customermeasurements') ? [] : ['className' => 'App\Model\Table\CustomermeasurementsTable'];
        $this->Customermeasurements = TableRegistry::get('Customermeasurements', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Customermeasurements);

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
