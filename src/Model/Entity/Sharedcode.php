<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Sharedcode Entity.
 *
 * @property int $id
 * @property int $client_id
 * @property \App\Model\Entity\Client $client
 * @property int $customer_id
 * @property \App\Model\Entity\Customer $customer
 * @property string $code
 * @property string $type
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property string $redeemed_count
 * @property string $created_at
 * @property \App\Model\Entity\SharedcodeRedeemed[] $sharedcode_redeemed
 */
class Sharedcode extends Entity
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
    protected function _setCreatedAt($dval){
        return time(); //$dval->timestamp;
    }
}
