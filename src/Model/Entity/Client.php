<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Client Entity.
 *
 * @property int $id
 * @property string $senderid
 * @property string $plivo_authid
 * @property string $plivo_auth_token
 * @property string $slug
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $pwd
 * @property string $contact_no
 * @property string $image
 * @property string $address
 * @property int $sms_quantity
 * @property int $sms_sent
 * @property int $email_left
 * @property int $email_sent
 * @property string $pass_hash
 * @property int $status
 * @property int $soft_deleted
 * @property int $created
 * @property int $modified
 * @property int $max_device_limit
 * @property \App\Model\Entity\Brand[] $brands
 * @property \App\Model\Entity\Device[] $devices
 * @property \App\Model\Entity\Sharedcode[] $sharedcodes
 * @property \App\Model\Entity\SocialConnection[] $social_connections
 * @property \App\Model\Entity\Socialshare[] $socialshares
 * @property \App\Model\Entity\Smsplan[] $smsplans
 */
class Client extends Entity
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
    protected function _setModified($dval){
        return time(); //$dval->timestamp;
    }
    
    protected function _setPassword($password){
        return (new \Cake\Auth\DefaultPasswordHasher)->hash($password);
    }
}
