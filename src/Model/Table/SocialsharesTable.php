<?php
namespace App\Model\Table;

use App\Model\Entity\Socialshare;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Socialshares Model
 *
 */
class SocialsharesTable extends Table
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

        $this->table('socialshares');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER'
        ]);
        $this->addBehavior('Timestamp');
        $this->addBehavior('Upload',[
            'imageQuality' => 80,
            'uploadField' => 'image',
            'config' => [
                'Socialshares' => [
                    'sizes' => [
                        '220x0' => [220, 0],
                        '640x640' => [640, 640],
                    ],
                    'dirPattern' => "{WWW_ROOT}uploads{DS}socialPost{DS}", // http://v3.zakoopi.com/uploads/markets/ + name + -size.jpg
                    'slugColumns' => ['description']
                ]
            ]
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
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->add('status', 'valid', ['rule' => 'numeric'])
            ->requirePresence('status', 'create')
            ->notEmpty('status');
        
        return $validator;
    }
}
