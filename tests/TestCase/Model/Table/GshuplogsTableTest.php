<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GshuplogsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GshuplogsTable Test Case
 */
class GshuplogsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GshuplogsTable
     */
    public $Gshuplogs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.gshuplogs',
        'app.externals'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Gshuplogs') ? [] : ['className' => 'App\Model\Table\GshuplogsTable'];
        $this->Gshuplogs = TableRegistry::get('Gshuplogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Gshuplogs);

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
