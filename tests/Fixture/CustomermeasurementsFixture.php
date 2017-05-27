<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CustomermeasurementsFixture
 *
 */
class CustomermeasurementsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'customer_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'measurement_type' => ['type' => 'string', 'length' => 70, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'measurement_name' => ['type' => 'string', 'length' => 70, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'value' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'customer_id' => 1,
            'measurement_type' => 'Lorem ipsum dolor sit amet',
            'measurement_name' => 'Lorem ipsum dolor sit amet',
            'value' => 'Lorem ip',
            'created' => 1
        ],
    ];
}
