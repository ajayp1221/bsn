<?php
namespace App\Model\Table;

use App\Model\Entity\Smsledger;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Smsledger Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Clients
 * @property \Cake\ORM\Association\BelongsTo $Stores
 * @property \Cake\ORM\Association\BelongsTo $Customers
 */
class SmsledgerTable extends Table
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

        $this->table('smsledger');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
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
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

//        $validator
//            ->requirePresence('comments', 'create')
//            ->notEmpty('comments');
//
//        $validator
//            ->add('credit', 'valid', ['rule' => 'numeric'])
//            ->requirePresence('credit', 'create')
//            ->notEmpty('credit');
//
//        $validator
//            ->add('debit', 'valid', ['rule' => 'numeric'])
//            ->requirePresence('debit', 'create')
//            ->notEmpty('debit');
//
//        $validator
//            ->add('balance', 'valid', ['rule' => 'numeric'])
//            ->requirePresence('balance', 'create')
//            ->notEmpty('balance');
//
//        $validator
//            ->requirePresence('extra', 'create')
//            ->notEmpty('extra');

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
        $rules->add($rules->existsIn(['client_id'], 'Clients'));
//        $rules->add($rules->existsIn(['store_id'], 'Stores'));
//        $rules->add($rules->existsIn(['customer_id'], 'Customers'));
        return $rules;
    }
    
    /**
     * 
     * @param type $credit
     * @param type $client_id
     * @param type $comments
     * @param type $customer_id
     * @param type $store_id
     * @param type $extras
     */
    public function addCredit($credit,$client_id,$comments,$customer_id = 0,$store_id = 0,$extras = '',$number = ''){
        $bl = $this->findByClientId($client_id)->last();
        $ent = $this->newEntity([
            "client_id" => $client_id,
            "customer_id" => $customer_id,
            "store_id" => $store_id,
            "credit" => $credit,
            "debit" => 0,
            "balance" => $bl->balance + $credit,
            "extra" => $extras,
            "comments" => $comments,
            "number" => $number
        ]);
        if($x = $this->save($ent)){
            return $x;
        }
        return $ent;
    }
    
    
    /**
     * 
     * @param type $debit
     * @param type $client_id
     * @param type $comments
     * @param type $customer_id
     * @param type $store_id
     * @param type $extras
     */
    public function addDebit($debit,$client_id,$comments,$customer_id = 0,$store_id = 0,$extras = '',$number = ''){
        $bl = $this->findByClientId($client_id)->last();
        $ent = $this->newEntity([
            "client_id" => $client_id,
            "customer_id" => $customer_id,
            "store_id" => $store_id,
            "credit" => 0,
            "debit" => $debit,
            "balance" => $bl->balance - $debit,
            "extra" => $extras,
            "comments" => $comments,
            "number" => $number
        ]);
        if($x = $this->save($ent)){
            return $x;
        }
        return $ent;
    }
}
