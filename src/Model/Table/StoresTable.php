<?php
namespace App\Model\Table;

use App\Model\Entity\Store;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Stores Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Brands
 * @property \Cake\ORM\Association\HasMany $Albums
 * @property \Cake\ORM\Association\HasMany $Campaigns
 * @property \Cake\ORM\Association\HasMany $CustomerVisits
 * @property \Cake\ORM\Association\HasMany $Customers
 * @property \Cake\ORM\Association\HasMany $Messages
 * @property \Cake\ORM\Association\HasMany $Productcats
 * @property \Cake\ORM\Association\HasMany $Products
 * @property \Cake\ORM\Association\HasMany $Purchases
 * @property \Cake\ORM\Association\HasMany $Pushmessages
 * @property \Cake\ORM\Association\HasMany $Questions
 * @property \Cake\ORM\Association\HasMany $RecommendScreen
 * @property \Cake\ORM\Association\HasMany $ShareScreen
 * @property \Cake\ORM\Association\HasMany $Sharedcodes
 * @property \Cake\ORM\Association\HasMany $Templatemessages
 * @property \Cake\ORM\Association\HasMany $Welcomemsgs
 * @property \Cake\ORM\Association\HasMany $Smsledger
 */
class StoresTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('stores');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Brands', [
            'foreignKey' => 'brand_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Albums', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('Campaigns', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('CustomerVisits', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('Customers', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('Messages', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('Productcats', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('Products', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('Smsledger', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('Purchases', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('Pushmessages', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('Questions', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('RecommendScreen', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('ShareScreen', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('Sharedcodes', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('Templatemessages', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
        $this->hasMany('Welcomemsgs', [
            'foreignKey' => 'store_id',
            'dependent' => true
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('address', 'create')
            ->notEmpty('address');

        $validator
            ->add('status', 'valid', ['rule' => 'numeric'])
            ->requirePresence('status', 'create')
            ->notEmpty('status');


        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['brand_id'], 'Brands'));
        return $rules;
    }
}
