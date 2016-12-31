<?php
namespace Banana\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AttributesModelValues Model
 *
 * @property \Cake\ORM\Association\BelongsTo $AttributeSets
 * @property \Cake\ORM\Association\BelongsTo $Attributes
 *
 * @method \Banana\Model\Entity\AttributesModelValue get($primaryKey, $options = [])
 * @method \Banana\Model\Entity\AttributesModelValue newEntity($data = null, array $options = [])
 * @method \Banana\Model\Entity\AttributesModelValue[] newEntities(array $data, array $options = [])
 * @method \Banana\Model\Entity\AttributesModelValue|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Banana\Model\Entity\AttributesModelValue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Banana\Model\Entity\AttributesModelValue[] patchEntities($entities, array $data, array $options = [])
 * @method \Banana\Model\Entity\AttributesModelValue findOrCreate($search, callable $callback = null)
 */
class AttributesModelValuesTable extends Table
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

        $this->table('bc_attributes_model_values');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('AttributeSets', [
            'foreignKey' => 'attribute_set_id',
            'className' => 'Banana.AttributeSets'
        ]);
        $this->belongsTo('Attributes', [
            'foreignKey' => 'attribute_id',
            'joinType' => 'INNER',
            'className' => 'Banana.Attributes'
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
            ->requirePresence('model', 'create')
            ->notEmpty('model');

        $validator
            ->integer('modelid')
            ->requirePresence('modelid', 'create')
            ->notEmpty('modelid');

        $validator
            ->allowEmpty('value');

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
        $rules->add($rules->existsIn(['attribute_set_id'], 'AttributeSets'));
        $rules->add($rules->existsIn(['attribute_id'], 'Attributes'));

        return $rules;
    }
}
