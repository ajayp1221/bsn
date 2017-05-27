<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Question Entity.
 *
 * @property int $id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property string $question
 * @property int $question_type
 * @property int $status
 * @property int $soft_deleted
 * @property int $created
 * @property string $view_type
 * @property bool $no_delete
 * @property bool $no_skip
 * @property int $order_seq
 * @property string $buyer
 * @property string $group_mark
 * @property \App\Model\Entity\Answer[] $answers
 * @property \App\Model\Entity\Option[] $options
 */
class Question extends Entity
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
