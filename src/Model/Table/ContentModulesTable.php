<?php
namespace Banana\Model\Table;

use Banana\Model\Entity\PageModule;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PageModules Model
 */
class ContentModulesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('bc_content_modules');
        $this->displayField('id');
        $this->primaryKey('id');
        /*
        $this->belongsTo('Pages', [
            'foreignKey' => 'page_id',
            'className' => 'Banana.Pages'
        ]);
        */
        $this->belongsTo('Modules', [
            'foreignKey' => 'module_id',
            'joinType' => 'INNER',
            'className' => 'Banana.Modules'
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
            ->add('refscope', 'valid', ['rule' => 'notblank']);

        $validator
            ->add('refid', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('refid');

        $validator
            //->add('template', 'valid', ['rule' => 'alphanumeric'])
            ->allowEmpty('template');

        $validator
            ->add('section', 'valid', ['rule' => 'alphanumeric'])
            ->allowEmpty('section');

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
        $rules->add($rules->existsIn(['page_id'], 'Pages'));
        $rules->add($rules->existsIn(['module_id'], 'Modules'));
        return $rules;
    }
}
