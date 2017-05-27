<?php
namespace App\Model\Table;

use App\Model\Entity\StoreCoverImage;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StoreCoverImages Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Stores
 */
class StoreCoverImagesTable extends Table
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

        $this->table('store_cover_images');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('Upload',[
            'imageQuality' => 80,
            'uploadField' => 'image',
            'config' => [
                'StoreCoverImages' => [
                    'sizes' => [
                        '720x0' => [720, 0],
                        '220x0' => [220, 0],
                        '36x0' => [36, 0],
                    ],
                    'dirPattern' => "{WWW_ROOT}uploads{DS}bsnstorecoverimages{DS}", // https://s3-ap-southeast-1.amazonaws.com/bsn-zkp/uploads/bsnstorecoverimages/ + name + -size.jpg
                    'slugColumns' => ['created']
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
