<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Customermeasurement Entity.
 *
 * @property int $id
 * @property int $customer_id
 * @property \App\Model\Entity\Customer $customer
 * @property string $measurement_type
 * @property string $measurement_name
 * @property string $value
 * @property int $created
 */
class Customermeasurement extends Entity
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
