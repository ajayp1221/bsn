<?php
namespace App\Model\Table;

use App\Model\Entity\Customer;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Customers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Stores
 * @property \Cake\ORM\Association\HasMany $Answers
 * @property \Cake\ORM\Association\HasMany $CustomerVisits
 * @property \Cake\ORM\Association\HasMany $Messages
 * @property \Cake\ORM\Association\HasMany $Purchases
 * @property \Cake\ORM\Association\HasMany $SharedcodeRedeemed
 * @property \Cake\ORM\Association\HasMany $Sharedcodes
 * @property \Cake\ORM\Association\HasMany $Smsledger
 * @property \Cake\ORM\Association\HasMany $Welcomemsgs
 * @property \Cake\ORM\Association\HasMany $Customermeasurements
 */
class CustomersTable extends Table
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

        $this->table('customers');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Answers', [
            'foreignKey' => 'customers_id',
            'dependent' => true
        ]);
        $this->hasMany('CustomerVisits', [
            'foreignKey' => 'customer_id',
            'dependent' => true
        ]);
        $this->hasMany('Smsledger', [
            'foreignKey' => 'customer_id',
            'dependent' => true
        ]);
        $this->hasMany('Messages', [
            'foreignKey' => 'customer_id',
            'dependent' => true
        ]);
        $this->hasMany('Purchases', [
            'foreignKey' => 'customer_id',
            'dependent' => true
        ]);
        $this->hasMany('SharedcodeRedeemed', [
            'foreignKey' => 'customer_id',
            'dependent' => true
        ]);
        $this->hasMany('Sharedcodes', [
            'foreignKey' => 'customer_id',
            'dependent' => true
        ]);
        $this->hasMany('Welcomemsgs', [
            'foreignKey' => 'customer_id',
            'dependent' => true
        ]);
        $this->hasMany('Customermeasurements', [
            'foreignKey' => 'customer_id',
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
            ->integer('id')
            ->allowEmpty('id', 'create');

//        $validator
//            ->requirePresence('contact_no', 'create')
//            ->notEmpty('contact_no');
//
//        $validator
//            ->email('email')
//            ->requirePresence('email', 'create')
//            ->notEmpty('email');
//
//        $validator
//            ->requirePresence('name', 'create')
//            ->notEmpty('name');
//
//        $validator
//            ->integer('gender')
//            ->requirePresence('gender', 'create')
//            ->notEmpty('gender');
//
//        $validator
//            ->requirePresence('dob', 'create')
//            ->notEmpty('dob');
//
//        $validator
//            ->requirePresence('doa', 'create')
//            ->notEmpty('doa');
//
//        $validator
//            ->requirePresence('added_by', 'create')
//            ->notEmpty('added_by');
//
//        $validator
//            ->boolean('opt_in')
//            ->requirePresence('opt_in', 'create')
//            ->notEmpty('opt_in');
//
//        $validator
//            ->integer('status')
//            ->requirePresence('status', 'create')
//            ->notEmpty('status');
//
//        $validator
//            ->integer('soft_deleted')
//            ->requirePresence('soft_deleted', 'create')
//            ->notEmpty('soft_deleted');
//
//        $validator
//            ->integer('age')
//            ->requirePresence('age', 'create')
//            ->notEmpty('age');
//
//        $validator
//            ->integer('refered_by')
//            ->requirePresence('refered_by', 'create')
//            ->notEmpty('refered_by');
//
//        $validator
//            ->requirePresence('spouse_name', 'create')
//            ->notEmpty('spouse_name');
//
//        $validator
//            ->requirePresence('spouse_date', 'create')
//            ->notEmpty('spouse_date');
//
//        $validator
//            ->requirePresence('kids_info', 'create')
//            ->notEmpty('kids_info');

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
//        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['store_id'], 'Stores'));
        return $rules;
    }
}
