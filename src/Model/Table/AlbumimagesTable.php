<?php
namespace App\Model\Table;

use App\Model\Entity\Albumimage;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Albumimages Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Albums
 */
class AlbumimagesTable extends Table
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

        $this->table('albumimages');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('Upload',[
            'imageQuality' => 80,
            'uploadField' => 'image',
            'config' => [
                'Albumimages' => [
                    'sizes' => [
                        '720x0' => [720, 0],
                        '220x0' => [220, 0],
                        '36x0' => [36, 0],
                    ],
                    'dirPattern' => "{WWW_ROOT}uploads{DS}albumimages{DS}", // http://v3.zakoopi.com/uploads/albumimages/ + name + -size.jpg
                    'slugColumns' => ['description','created']
                ]
            ]
        ]);
        
        $this->belongsTo('Albums', [
            'foreignKey' => 'album_id',
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

//        $validator
//            ->requirePresence('image', 'create')
//            ->notEmpty('image');
//
//        $validator
//            ->requirePresence('description', 'create')
//            ->notEmpty('description');
//
//        $validator
//            ->requirePresence('price', 'create')
//            ->notEmpty('price');
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
        $rules->add($rules->existsIn(['album_id'], 'Albums'));
        return $rules;
    }
}
