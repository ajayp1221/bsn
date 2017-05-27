<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Loyaltymember Entity.
 *
 * @property int $id
 * @property int $customer_id
 * @property \App\Model\Entity\Customer $customer
 * @property int $total_points
 * @property int $points_left
 * @property int $points_used
 * @property float $ratio
 * @property string $created
 * @property bool $status
 * @property \App\Model\Entity\Loyaltyledger[] $loyaltyledger
 */
class Loyaltymember extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
