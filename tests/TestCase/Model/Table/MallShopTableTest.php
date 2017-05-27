<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MallShopTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MallShopTable Test Case
 */
class MallShopTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MallShopTable
     */
    public $MallShop;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.mall_shop'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MallShop') ? [] : ['className' => 'App\Model\Table\MallShopTable'];
        $this->MallShop = TableRegistry::get('MallShop', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MallShop);

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
