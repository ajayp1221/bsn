<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity.
 *
 * @property int $id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property string $category
 * @property string $product_name
 * @property string $purchase_count
 * @property int $productcat_id
 * @property \App\Model\Entity\Productcat $productcat
 * @property \App\Model\Entity\Purchase[] $purchases
 */
class Product extends Entity
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
    protected function _setCreated($dval){
        return time(); //$dval->timestamp;
    }
}
