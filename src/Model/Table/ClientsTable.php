<?php
namespace App\Model\Table;

use App\Model\Entity\Client;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Clients Model
 *
 * @property \Cake\ORM\Association\HasMany $Brands
 * @property \Cake\ORM\Association\HasMany $Devices
 * @property \Cake\ORM\Association\HasMany $Sharedcodes
 * @property \Cake\ORM\Association\HasMany $SocialConnections
 * @property \Cake\ORM\Association\HasMany $Socialshares
 * @property \Cake\ORM\Association\HasMany $Smsledger
 * @property \Cake\ORM\Association\BelongsToMany $Smsplans
 */
class ClientsTable extends Table
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

        $this->table('clients');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Brands', [
            'foreignKey' => 'client_id',
            'dependent' => true
        ]);
        $this->hasMany('Devices', [
            'foreignKey' => 'client_id',
            'dependent' => true
        ]);
        $this->hasMany('Sharedcodes', [
            'foreignKey' => 'client_id',
            'dependent' => true
        ]);
        $this->hasMany('SocialConnections', [
            'foreignKey' => 'client_id',
            'dependent' => true
        ]);
        $this->hasMany('Socialshares', [
            'foreignKey' => 'client_id',
            'dependent' => true
        ]);
        $this->hasMany('Smsledger', [
            'foreignKey' => 'client_id',
            'dependent' => true
        ]);
        $this->belongsToMany('Smsplans', [
            'foreignKey' => 'client_id',
            'targetForeignKey' => 'smsplan_id',
            'joinTable' => 'clients_smsplans'
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
            ->add('email', 'valid', ['rule' => 'email'])
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->add('contact_no', 'valid', ['rule' => 'numeric'])
            ->requirePresence('contact_no', 'create')
            ->notEmpty('contact_no');

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
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
}
