<?php
namespace Banana\Model\Table;

use Banana\Model\Entity\Page;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Banana\Model\Table\PageModulesTable;

/**
 * Pages Model
 *
 * @property PageModulesTable $PageModules
 */
class PagesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('bc_pages');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree.Tree');
        $this->addBehavior('Banana.ContentModule', [
            'alias' => 'ContentModules',
            'scope' => 'Banana.Pages'
        ]);
        $this->addBehavior('Banana.Copyable', [
            'excludeFields' => ['lft', 'rght', 'slug']
        ]);
        $this->addBehavior('Banana.Sluggable');

        $this->belongsTo('ParentPages', [
            'className' => 'Banana.Pages',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildPages', [
            'className' => 'Banana.Pages',
            'foreignKey' => 'parent_id'
        ]);

        /*
        $this->hasMany('PageModules', [
            'className' => 'Banana.PageModules',
            'foreignKey' => 'page_id'
        ]);
        */

        /*
        $this->hasMany('PageModules', [
            'className' => 'Banana.ContentModules',
            'foreignKey' => 'refid',
            'conditions' => ['refscope' => 'Banana.Pages']
        ]);
        */
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
            ->add('lft', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('lft');
            
        $validator
            ->add('rght', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('rght');
            
        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');
            
        $validator
            //->requirePresence('slug', 'create')
            ->allowEmpty('slug');

        $validator
            ->allowEmpty('type');

        $validator
            ->add('redirect_status', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('redirect_status');

        $validator
            ->allowEmpty('redirect_location');

        $validator
            ->allowEmpty('redirect_controller');

        $validator
            ->add('redirect_page_id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('redirect_page_id');

        $validator
            ->allowEmpty('layout_template');
            
        $validator
            ->allowEmpty('page_template');
            
        $validator
            ->add('is_published', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_published');
            
        $validator
            ->add('publish_start_date', 'valid', ['rule' => 'date'])
            ->allowEmpty('publish_start_date');
            
        $validator
            ->add('publish_end_date', 'valid', ['rule' => 'date'])
            ->allowEmpty('publish_end_date');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentPages'));
        return $rules;
    }
}
