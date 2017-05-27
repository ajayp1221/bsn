<?php
namespace App\Model\Table;

use App\Model\Entity\Albumshare;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Albumshares Model
 *
 */
class AlbumsharesTable extends Table
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

        $this->table('albumshares');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
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
/*
        $validator
            ->requirePresence('contact_no', 'create')
            ->notEmpty('contact_no');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->requirePresence('ids', 'create')
            ->notEmpty('ids');

        $validator
            ->requirePresence('bucket_name', 'create')
            ->notEmpty('bucket_name');

        $validator
            ->requirePresence('link', 'create')
            ->notEmpty('link');

        $validator
            ->requirePresence('expired', 'create')
            ->notEmpty('expired');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->integer('soft_deleted')
            ->requirePresence('soft_deleted', 'create')
            ->notEmpty('soft_deleted');

        $validator
            ->integer('maxviews')
            ->requirePresence('maxviews', 'create')
            ->notEmpty('maxviews');

        $validator
            ->integer('views')
            ->requirePresence('views', 'create')
            ->notEmpty('views');
*/
        return $validator;
    }

}
