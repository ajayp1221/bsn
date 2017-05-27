<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ClientsSmsplan Entity.
 *
 * @property int $id
 * @property int $client_id
 * @property \App\Model\Entity\Client $client
 * @property int $smsplan_id
 * @property \App\Model\Entity\Smsplan $smsplan
 * @property int $created
 */
class ClientsSmsplan extends Entity
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
        return time();
    }
}
