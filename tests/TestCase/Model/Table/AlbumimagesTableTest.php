<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AlbumimagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AlbumimagesTable Test Case
 */
class AlbumimagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AlbumimagesTable
     */
    public $Albumimages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.albumimages',
        'app.albums'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Albumimages') ? [] : ['className' => 'App\Model\Table\AlbumimagesTable'];
        $this->Albumimages = TableRegistry::get('Albumimages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Albumimages);

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
