<?php
namespace Banana\Model\Table;

use Banana\Model\Entity\Module;
use Cake\Core\App;
use Cake\Event\Event;
use Cake\Database\Schema;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Modules Model
 *
 */
class ModulesTable extends Table
{
    protected function _initializeSchema(Schema\Table $table)
    {
        //$table->columnType('params', 'json');
        return $table;
    }

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('bc_modules');
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');
            
        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');
            
        $validator
            ->allowEmpty('title');
            
        $validator
            ->requirePresence('path', 'create')
            ->notEmpty('path');
            
        $validator
            ->allowEmpty('params');

        return $validator;
    }

    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options)
    {
        if (isset($data['path'])) {
            $entityClass = self::moduleEntityClass($data['path']);
            $this->entityClass($entityClass);
        }
    }

    public static function moduleEntityClass($moduleClass)
    {
        return App::className($moduleClass, 'Model/Entity/Module', 'Module');
    }

    public function modularize($entity)
    {
        $entityClass = self::moduleEntityClass($entity->path);
        $this->entityClass($entityClass);

        $mod = $this->newEntity();
        $mod->accessible('*', true);
        $mod->set($entity->toArray());
        return $mod;
    }

    public function findExpanded(Query $query, array $options)
    {
        // Incomplete
        debug($options);
        if (isset($options['path'])) {
            $entityClass = self::moduleEntityClass($options['path']);
            $this->entityClass($entityClass);
        }
        return $query;
    }
}
