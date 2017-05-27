<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Pushmessage Entity.
 *
 * @property int $id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property string $store_slug
 * @property string $at_time
 * @property string $image
 * @property string $title
 * @property string $message
 * @property int $status
 * @property int $soft_deleted
 * @property int $created
 * @property int $modified
 */
class Pushmessage extends Entity
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
    /**
     * android_api_img
     * @return type
     */
    protected function _getAndroidApiImg(){
        return 'https://s3-ap-southeast-1.amazonaws.com/bsn-zkp/uploads/pushmessages/'.$this->_properties['image']."-720x0.jpg";
    }
}
