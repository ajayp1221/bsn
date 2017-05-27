<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StoreImage Entity.
 *
 * @property int $id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property string $name
 * @property string $desc
 * @property string $price
 * @property string $medium_img_w
 * @property string $medium_img_h
 * @property int $created
 */
class StoreImage extends Entity
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
    protected function _setCreated(){
        return time();
    }
    
    protected $_virtual = ['android_api_img','android_api_img_large','android_api_img_medium'];
    /**
     * android_api_img
     * @return type
     */
    protected function _getAndroidApiImg(){
        return 'https://s3-ap-southeast-1.amazonaws.com/bsn-zkp/uploads/bsnstoreimages/'.$this->_properties['image'].".jpg";
    }
    /**
     * android_api_img_large
     * @return type
     */
    protected function _getAndroidApiImgLarge(){
        return 'https://s3-ap-southeast-1.amazonaws.com/bsn-zkp/uploads/bsnstoreimages/'.$this->_properties['image']."-720x0.jpg";
    }
    /**
     * android_api_img_medium
     * @return type
     */
    protected function _getAndroidApiImgMedium(){
        return 'https://s3-ap-southeast-1.amazonaws.com/bsn-zkp/uploads/bsnstoreimages/'.$this->_properties['image']."-220x0.jpg";
    }
}
