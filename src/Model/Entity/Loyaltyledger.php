<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Loyaltyledger Entity.
 *
 * @property int $id
 * @property int $loyaltymember_id
 * @property \App\Model\Entity\Loyaltymember $loyaltymember
 * @property int $points_credit
 * @property int $points_debit
 * @property int $points_balance
 * @property string $comments
 * @property string $dated
 * @property float $ratio
 */
class Loyaltyledger extends Entity
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
