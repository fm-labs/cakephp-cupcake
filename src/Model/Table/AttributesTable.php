<?php
namespace Banana\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Attributes Model
 *
 * @method \Banana\Model\Entity\Attribute get($primaryKey, $options = [])
 * @method \Banana\Model\Entity\Attribute newEntity($data = null, array $options = [])
 * @method \Banana\Model\Entity\Attribute[] newEntities(array $data, array $options = [])
 * @method \Banana\Model\Entity\Attribute|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Banana\Model\Entity\Attribute patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Banana\Model\Entity\Attribute[] patchEntities($entities, array $data, array $options = [])
 * @method \Banana\Model\Entity\Attribute findOrCreate($search, callable $callback = null)
 */
class AttributesTable extends Table
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

        $this->table('bc_attributes');
        $this->displayField('name');
        $this->primaryKey('id');
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
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->boolean('is_required')
            ->requirePresence('is_required', 'create')
            ->notEmpty('is_required');

        $validator
            ->boolean('is_searchable')
            ->requirePresence('is_searchable', 'create')
            ->notEmpty('is_searchable');

        $validator
            ->boolean('is_filterable')
            ->requirePresence('is_filterable', 'create')
            ->notEmpty('is_filterable');

        $validator
            ->boolean('is_protected')
            ->requirePresence('is_protected', 'create')
            ->notEmpty('is_protected');

        $validator
            ->allowEmpty('ref');

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
        $rules->add($rules->isUnique(['name']));

        return $rules;
    }
}
