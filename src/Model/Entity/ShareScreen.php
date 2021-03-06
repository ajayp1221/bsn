<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShareScreen Entity.
 *
 * @property int $id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property int $active
 * @property string $header_text
 * @property string $description
 * @property string $created
 * @property string $popup_title
 * @property string $popup_message
 * @property string $popup_url
 * @property int $embedcode
 */
class ShareScreen extends Entity
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
        return time();
    }
}
