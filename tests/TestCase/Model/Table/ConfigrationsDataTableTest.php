<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConfigrationsDataTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConfigrationsDataTable Test Case
 */
class ConfigrationsDataTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ConfigrationsDataTable
     */
    public $ConfigrationsData;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.configrations_data'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ConfigrationsData') ? [] : ['className' => 'App\Model\Table\ConfigrationsDataTable'];
        $this->ConfigrationsData = TableRegistry::get('ConfigrationsData', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ConfigrationsData);

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
}
