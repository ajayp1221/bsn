<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Customer Entity.
 *
 * @property int $id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property string $contact_no
 * @property string $email
 * @property string $name
 * @property int $gender
 * @property string $dob
 * @property string $doa
 * @property string $added_by
 * @property bool $opt_in
 * @property int $status
 * @property int $soft_deleted
 * @property string $created
 * @property int $age
 * @property int $refered_by
 * @property string $spouse_name
 * @property string $spouse_date
 * @property string $kids_info
 * @property \App\Model\Entity\CustomerVisit[] $customer_visits
 * @property \App\Model\Entity\Message[] $messages
 * @property \App\Model\Entity\Purchase[] $purchases
 * @property \App\Model\Entity\SharedcodeRedeemed[] $sharedcode_redeemed
 * @property \App\Model\Entity\Sharedcode[] $sharedcodes
 * @property \App\Model\Entity\Welcomemsg[] $welcomemsgs
 */
class Customer extends Entity
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
