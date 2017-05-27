<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Exclude Entity.
 *
 * @property int $id
 * @property string $model_name
 * @property string $value
 * @property int $status
 * @property int $soft_deleted
 * @property int $created
 * @property int $modified
 */
class Exclude extends Entity
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
