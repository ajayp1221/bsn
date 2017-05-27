<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Albumshare Entity.
 *
 * @property int $id
 * @property string $contact_no
 * @property string $email
 * @property string $password
 * @property string $type
 * @property string $ids
 * @property string $bucket_name
 * @property string $link
 * @property string $expired
 * @property string $created
 * @property int $status
 * @property int $soft_deleted
 * @property int $maxviews
 * @property int $views
 */
class Albumshare extends Entity
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

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
    protected function _setCreated($dval){
        return time(); //$dval->timestamp;
    }
}
