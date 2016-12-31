<?php
namespace Banana\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AttributeSets Model
 *
 * @method \Banana\Model\Entity\AttributeSet get($primaryKey, $options = [])
 * @method \Banana\Model\Entity\AttributeSet newEntity($data = null, array $options = [])
 * @method \Banana\Model\Entity\AttributeSet[] newEntities(array $data, array $options = [])
 * @method \Banana\Model\Entity\AttributeSet|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Banana\Model\Entity\AttributeSet patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Banana\Model\Entity\AttributeSet[] patchEntities($entities, array $data, array $options = [])
 * @method \Banana\Model\Entity\AttributeSet findOrCreate($search, callable $callback = null)
 */
class AttributeSetsTable extends Table
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

        $this->table('bc_attribute_sets');
        $this->displayField('title');
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
            ->requirePresence('title', 'create')
            ->notEmpty('title')
            ->add('title', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
        $rules->add($rules->isUnique(['title']));

        return $rules;
    }
}
