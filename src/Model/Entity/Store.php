<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Store Entity.
 *
 * @property int $id
 * @property int $zkpstoreid
 * @property int $brand_id
 * @property \App\Model\Entity\Brand $brand
 * @property string $name
 * @property string $slug
 * @property string $address
 * @property int $status
 * @property int $soft_deleted
 * @property int $created
 * @property string $contact_numbers
 * @property string $emails
 * @property string $links
 * @property string $timings
 * @property string $closed
 * @property string $location_cordinates
 * @property \App\Model\Entity\Album[] $albums
 * @property \App\Model\Entity\Campaign[] $campaigns
 * @property \App\Model\Entity\CustomerVisit[] $customer_visits
 * @property \App\Model\Entity\Customer[] $customers
 * @property \App\Model\Entity\Message[] $messages
 * @property \App\Model\Entity\Productcat[] $productcats
 * @property \App\Model\Entity\Product[] $products
 * @property \App\Model\Entity\Purchase[] $purchases
 * @property \App\Model\Entity\Pushmessage[] $pushmessages
 * @property \App\Model\Entity\Question[] $questions
 * @property \App\Model\Entity\RecommendScreen[] $recommend_screen
 * @property \App\Model\Entity\ShareScreen[] $share_screen
 * @property \App\Model\Entity\Sharedcode[] $sharedcodes
 * @property \App\Model\Entity\Templatemessage[] $templatemessages
 * @property \App\Model\Entity\Welcomemsg[] $welcomemsgs
 */
class Store extends Entity
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
    protected function _getContactNumbers($cnums){
        if($cnums == "" || $cnums == null)
            return [];
        return explode(",", $cnums);
    }
    
    protected function _setContactNumbers($cnums){
        return implode(",", $cnums);
    }
    
    protected function _getEmails($emails){
        if($emails == "" || $emails == null)
            return [];
        return explode(",", $emails);
    }
    
    protected function _setEmails($emails){
        return implode(",", $emails);
    }
    
    protected function _getLinks($links){
        if($links == "" || $links == null)
            return [];
        return explode(",", $links);
    }
    
    protected function _setLinks($links){
        return implode(",", $links);
    }

    protected function _getLocationCordinates($cords){
        if($cords == "" || $cords == null)
            return [];
        return explode(",", $cords);
    }
    
    protected function _setLocationCordinates($cords){
        return implode(",", $cords);
    }
}
