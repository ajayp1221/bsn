<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MallVerificationcounter Entity.
 *
 * @property int $id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property string $email
 * @property string $password
 * @property string $pwd
 * @property int $created
 * @property int $status
 */
class MallVerificationcounter extends Entity
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
    protected function _setCreated(){
        return time();
    }
    protected function _setPassword($password){
        return (new \Cake\Auth\DefaultPasswordHasher)->hash($password);
    }
}
