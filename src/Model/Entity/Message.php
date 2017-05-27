<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Message Entity.
 *
 * @property int $id
 * @property int $customer_id
 * @property \App\Model\Entity\Customer $customer
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property int $campaign_id
 * @property \App\Model\Entity\Campaign $campaign
 * @property string $promo_code
 * @property int $used
 * @property int $used_date
 * @property int $status
 * @property string $cause
 * @property int $received
 * @property string $open
 * @property string $api_key
 * @property string $api_response
 * @property int $soft_deleted
 * @property int $created
 * @property int $cost_multiplier
 * @property string $external_msgid
 */
class Message extends Entity
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
    protected function _setCostMultiplier($val = null){
        if(!$val){
            $val = 1;
            if(isset($this->_properties['campaign_id'])){
                $tbl = \Cake\ORM\TableRegistry::get("Campaigns");
                $cmp = $tbl->find()->where(['Campaigns.id'=>$this->_properties['campaign_id']])->first();
                if(isset($cmp->cost_multiplier)){
                    $val = $cmp->cost_multiplier;
                }
            }
        }
        return $val;
    }
}
