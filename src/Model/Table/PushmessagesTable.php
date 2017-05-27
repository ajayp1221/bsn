<?php
namespace App\Model\Table;

use App\Model\Entity\Pushmessage;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pushmessages Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Stores
 */
class PushmessagesTable extends Table
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

        $this->table('pushmessages');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Upload',[
            'imageQuality' => 80,
            'uploadField' => 'image',
            'config' => [
                'Pushmessages' => [
                    'sizes' => [
                        '720x0' => [720, 0],
                        '36x0' => [36, 0],
                    ],
                    'dirPattern' => "{WWW_ROOT}uploads{DS}pushmessages{DS}", // http://v3.zakoopi.com/uploads/pushmessages/ + name + -size.jpg
                    'slugColumns' => ['description','created']
                ]
            ]
        ]);

        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('store_slug', 'create')
            ->notEmpty('store_slug');

        $validator
            ->requirePresence('at_time', 'create')
            ->notEmpty('at_time');
        
        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('message', 'create')
            ->notEmpty('message');


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
        $rules->add($rules->existsIn(['store_id'], 'Stores'));
        return $rules;
    }
}
