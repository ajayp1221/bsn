<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Smsledger Entity.
 *
 * @property int $id
 * @property int $client_id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property int $customer_id
 * @property \App\Model\Entity\Customer $customer
 * @property string $comments
 * @property int $credit
 * @property int $debit
 * @property int $balance
 * @property string $extra
 * @property string $created
 */
class Smsledger extends Entity
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
    
    
    protected function _setCreated($val){
        return time();
    }
}
