<?php
namespace App\Model\Table;

use App\Model\Entity\Product;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Products Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Stores
 * @property \Cake\ORM\Association\BelongsTo $Productcats
 * @property \Cake\ORM\Association\HasMany $Purchases
 */
class ProductsTable extends Table
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

        $this->table('products');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Productcats', [
            'foreignKey' => 'productcat_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Purchases', [
            'foreignKey' => 'product_id',
            'dependent' => true
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
//            ->requirePresence('category', 'create')
//            ->notEmpty('category');

        $validator
            ->requirePresence('product_name', 'create')
            ->notEmpty('product_name');

//        $validator
//            ->requirePresence('purchase_count', 'create')
//            ->notEmpty('purchase_count');

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
//        $rules->add($rules->existsIn(['productcat_id'], 'Productcats'));
        return $rules;
    }
}
