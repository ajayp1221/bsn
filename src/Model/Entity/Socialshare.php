<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Socialshare Entity.
 *
 * @property int $id
 * @property int $client_id
 * @property \App\Model\Entity\Client $client
 * @property string $image
 * @property string $description
 * @property int $facebook
 * @property int $twitter
 * @property int $instagram
 * @property int $status
 * @property int $soft_deleted
 * @property int $created
 * @property string $schedule_date
 */
class Socialshare extends Entity
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
