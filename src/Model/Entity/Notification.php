<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Notification Entity.
 *
 * @property int $id
 * @property string $store_slug
 * @property string $type
 * @property string $device_token
 * @property string $deviceid
 * @property int $view
 * @property int $status
 * @property int $soft_deleted
 * @property int $created
 * @property int $modified
 */
class Notification extends Entity
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
    protected function _setModified($dval){
        return time(); //$dval->timestamp;
    }
}
