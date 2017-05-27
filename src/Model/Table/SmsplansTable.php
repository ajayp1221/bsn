<?php
namespace App\Model\Table;

use App\Model\Entity\Smsplan;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Smsplans Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Clients
 */
class SmsplansTable extends Table
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

        $this->table('smsplans');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Clients', [
            'foreignKey' => 'smsplan_id',
            'targetForeignKey' => 'client_id',
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
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->add('sms', 'valid', ['rule' => 'numeric'])
            ->requirePresence('sms', 'create')
            ->notEmpty('sms');

        $validator
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->add('price', 'valid', ['rule' => 'numeric'])
            ->requirePresence('price', 'create')
            ->notEmpty('price');

        $validator
            ->add('status', 'valid', ['rule' => 'numeric'])
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        return $validator;
    }
}
