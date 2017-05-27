<?php
namespace App\Model\Table;

use App\Model\Entity\Loyaltyledger;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Loyaltyledger Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Loyaltymembers
 */
class LoyaltyledgerTable extends Table
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

        $this->table('loyaltyledger');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Loyaltymembers', [
            'foreignKey' => 'loyaltymember_id',
//            'joinType' => 'INNER'
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
            ->add('points_credit', 'valid', ['rule' => 'numeric'])
            ->requirePresence('points_credit', 'create')
            ->notEmpty('points_credit');

        $validator
            ->add('points_debit', 'valid', ['rule' => 'numeric'])
            ->requirePresence('points_debit', 'create')
            ->notEmpty('points_debit');

        $validator
            ->add('points_balance', 'valid', ['rule' => 'numeric'])
            ->requirePresence('points_balance', 'create')
            ->notEmpty('points_balance');

        $validator
            ->requirePresence('comments', 'create')
            ->notEmpty('comments');

        $validator
            ->requirePresence('dated', 'create')
            ->notEmpty('dated');

        $validator
            ->add('ratio', 'valid', ['rule' => 'decimal'])
            ->requirePresence('ratio', 'create')
            ->notEmpty('ratio');

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
        $rules->add($rules->existsIn(['loyaltymember_id'], 'Loyaltymembers'));
        return $rules;
    }
}
