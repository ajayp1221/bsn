<?php
namespace App\Model\Table;

use App\Model\Entity\SocialConnection;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SocialConnections Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Clients
 */
class SocialConnectionsTable extends Table
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

        $this->table('social_connections');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER'
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
//        $validator
//            ->integer('id')
//            ->allowEmpty('id', 'create');
//
//        $validator
//            ->requirePresence('model', 'create')
//            ->notEmpty('model');
//
//        $validator
//            ->requirePresence('key', 'create')
//            ->notEmpty('key');
//
//        $validator
//            ->requirePresence('secret', 'create')
//            ->notEmpty('secret');
//
//        $validator
//            ->requirePresence('access_token', 'create')
//            ->notEmpty('access_token');
//
//        $validator
//            ->requirePresence('raw_data', 'create')
//            ->notEmpty('raw_data');
//
//        $validator
//            ->email('email')
//            ->requirePresence('email', 'create')
//            ->notEmpty('email');
//
//        $validator
//            ->requirePresence('last_accessed', 'create')
//            ->notEmpty('last_accessed');
//
//        $validator
//            ->integer('status')
//            ->requirePresence('status', 'create')
//            ->notEmpty('status');

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['client_id'], 'Clients'));
        return $rules;
    }
}
