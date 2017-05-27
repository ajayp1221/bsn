<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Campaign Entity.
 *
 * @property int $id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property string $campaign_type
 * @property int $send_before_day
 * @property string $send_date
 * @property int $repeat
 * @property string $repeat_type
 * @property string $compaign_name
 * @property string $subject
 * @property string $message
 * @property int $whos_send
 * @property int $status
 * @property int $soft_deleted
 * @property int $created
 * @property bool $embedcode
 * @property string $send_at
 * @property int $cost_multiplier
 * @property \App\Model\Entity\Message[] $messages
 */
class Campaign extends Entity
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
//        'total_msg'
    ];

    protected function _setCreated($dval){
        return time(); //$dval->timestamp;
    }

    protected function _getTotalMsg($val){
        $msgsTbl = \Cake\ORM\TableRegistry::get('Messages');
        $msgCount = $msgsTbl->find()->where([
            'Messages.campaign_id' => $this->_properties['id'],
            'Messages.status' => 0
        ])->count();
        return $msgCount;
    }
    protected function _getTotalredeemed(){
        $cmpTbl = \Cake\ORM\TableRegistry::get('Messages');
        $count = $cmpTbl->find()->where([
            'Messages.campaign_id' => $this->_properties['id'],
            'Messages.used' => 1
        ])->count();
        return $count;
    }
    /**
     * Very Important
     * @param type $dval
     */
    public function _getTotalDelivered(){
        $conn = \Cake\Datasource\ConnectionManager::get('default');
        $result = $conn->execute("SELECT COUNT(*) as total_delivered FROM `messages` WHERE `campaign_id` = ".$this->_properties['id']." AND `cause` = 'SUCCESS'");
        return (int)$result->fetch()[0];
    }
    public function _getTotalQueued(){
        $conn = \Cake\Datasource\ConnectionManager::get('default');
        $result = $conn->execute("SELECT COUNT(*) as total_delivered FROM `messages` WHERE `campaign_id` = ".$this->_properties['id']."");
        return (int)$result->fetch()[0];
    }
    public function _getTotalCost(){
        $conn = \Cake\Datasource\ConnectionManager::get('default');
//        $result = $conn->execute("SELECT SUM(cost_multiplier) as total_cost FROM `messages` WHERE `campaign_id` = ".$this->_properties['id']." AND `cause` = 'SUCCESS'");
//        return (int)$result->fetch()[0];
        $result = $conn->execute("SELECT MAX(cost_multiplier) as max_cost FROM `messages` WHERE `campaign_id` = ".$this->_properties['id']."");
        return (int)$result->fetch()[0] * $this->_getTotalQueued();
    }


    protected function _setCostMultiplier($val = null){
        if(!$val){
            $val = 1;
            if(isset($this->_properties['message'])){
                $t = strlen($this->_properties['message']);
                
            }
            
        }
        return $val;
    }
}
