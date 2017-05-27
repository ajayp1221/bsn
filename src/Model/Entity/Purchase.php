<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Purchase Entity.
 *
 * @property int $id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property int $product_id
 * @property \App\Model\Entity\Product $product
 * @property int $customer_id
 * @property \App\Model\Entity\Customer $customer
 * @property string $product_name
 * @property int $qty
 * @property int $price
 * @property string $sold_on
 */
class Purchase extends Entity
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
