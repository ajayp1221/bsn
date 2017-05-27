<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Albumimage Entity.
 *
 * @property int $id
 * @property int $album_id
 * @property \App\Model\Entity\Album $album
 * @property string $image
 * @property string $description
 * @property string $price
 * @property int $status
 * @property int $soft_deleted
 * @property string $created
 */
class Albumimage extends Entity
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
    
    protected $_virtual = [
        'android_api_img','android_api_img_medium','android_api_img_large'
    ];
    protected function _setCreated($dval){
        return time(); //$dval->timestamp;
    }
    
    /**
     * android_api_img
     * @return type
     */
    protected function _getAndroidApiImg(){
        return 'https://s3-ap-southeast-1.amazonaws.com/bsn-zkp/uploads/albumimages/'.$this->_properties['image'].".jpg";
    }
    /**
     * android_api_img_large
     * @return type
     */
    protected function _getAndroidApiImgLarge(){
        return 'https://s3-ap-southeast-1.amazonaws.com/bsn-zkp/uploads/albumimages/'.$this->_properties['image']."-720x0.jpg";
    }
    /**
     * android_api_img_medium
     * @return type
     */
    protected function _getAndroidApiImgMedium(){
        return 'https://s3-ap-southeast-1.amazonaws.com/bsn-zkp/uploads/albumimages/'.$this->_properties['image']."-220x0.jpg";
    }
}
