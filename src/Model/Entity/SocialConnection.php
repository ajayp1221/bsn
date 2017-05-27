<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SocialConnection Entity.
 *
 * @property int $id
 * @property int $client_id
 * @property \App\Model\Entity\Client $client
 * @property string $model
 * @property string $key
 * @property string $secret
 * @property string $access_token
 * @property string $raw_data
 * @property string $email
 * @property string $last_accessed
 * @property int $status
 */
class SocialConnection extends Entity
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
}
