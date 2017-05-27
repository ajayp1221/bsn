<?php
namespace App\Model\Table;

use App\Model\Entity\Loyaltymember;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Loyaltymembers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Customers
 * @property \Cake\ORM\Association\HasMany $Loyaltyledger
 */
class LoyaltymembersTable extends Table
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

        $this->table('loyaltymembers');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Loyaltyledger', [
            'foreignKey' => 'loyaltymember_id'
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
            ->add('total_points', 'valid', ['rule' => 'numeric'])
            ->requirePresence('total_points', 'create')
            ->notEmpty('total_points');

        $validator
            ->add('points_left', 'valid', ['rule' => 'numeric'])
            ->requirePresence('points_left', 'create')
            ->notEmpty('points_left');

        $validator
            ->add('points_used', 'valid', ['rule' => 'numeric'])
            ->requirePresence('points_used', 'create')
            ->notEmpty('points_used');

        $validator
            ->add('ratio', 'valid', ['rule' => 'decimal'])
            ->requirePresence('ratio', 'create')
            ->notEmpty('ratio');

        $validator
            ->add('status', 'valid', ['rule' => 'boolean'])
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
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));
        return $rules;
    }
}
