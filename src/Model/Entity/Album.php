<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Album Entity.
 *
 * @property int $id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property string $name
 * @property string $description
 * @property int $status
 * @property int $soft_deleted
 * @property string $created
 * @property \App\Model\Entity\Albumimage[] $albumimages
 * @property \App\Model\Entity\Albumshare[] $albumshares
 */
class Album extends Entity
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
