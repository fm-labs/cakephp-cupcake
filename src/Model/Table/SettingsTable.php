<?php
namespace Banana\Model\Table;

use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Banana\Configure\Engine\SettingsConfig;

/**
 * Settings Model
 *
 */
class SettingsTable extends Table
{

    const TYPE_STRING = 'string';
    const TYPE_INT = 'int';
    const TYPE_DOUBLE = 'double';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_TEXT = 'text';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';
    const TYPE_JSON = 'json';
    const TYPE_XML = 'xml';
    const TYPE_SERIALIZED = 'serialized';
    const TYPE_OTHER = 'other' ; // @deprecated

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('bc_settings');
        $this->displayField('key');
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('scope');
            
        $validator
            ->requirePresence('key')
            ->notEmpty('key');

        $validator
            ->allowEmpty('title');

        $validator
            ->allowEmpty('type');
            
        $validator
            ->allowEmpty('value');

        return $validator;
    }

    public function afterSave(Event $event, Entity $entity, \ArrayObject $options)
    {
        //SettingsConfig::resetSettingsFilePath(SETTINGS, $entity->ref);
        //return true;
    }

    public function afterDelete(Event $event, Entity $entity, \ArrayObject $options)
    {
        //SettingsConfig::resetSettingsFilePath(SETTINGS, $entity->ref);
        //return true;
    }

    public function listByKeys()
    {
        //@TODO Refactor with Collection methods
        $list = [];
        $data = $this->find()->all()->toArray();
        array_walk($data, function ($entity) use (&$list) {
            $list[$entity->key] = $entity->id;
        });
        return $list;
    }

    public function listValueTypes()
    {
        $types = [
            static::TYPE_INT,
            static::TYPE_DOUBLE,
            static::TYPE_STRING,
            static::TYPE_TEXT,
            static::TYPE_DATE,
            static::TYPE_DATETIME,
            static::TYPE_BOOLEAN
        ];
        return array_combine($types, $types);
    }

    /*
    public function import($plugin = null)
    {
        // fetch existing settings
        $query = $this->find()->where(['Settings.scope IS' => $plugin])->order(['Settings.key' => 'ASC']);
        // compile existing settings
        $existent = [];
        foreach ($query->all() as $setting) {
            $existent[$setting->key] = $setting;
        }
        
        // read import settings schema
        $settingsPath = ($plugin) ? Plugin::configPath($plugin) : CONFIG;
        
        $schema = SettingsConfig::readSchema($settingsPath);

        $settings = [];
        foreach ($schema as $key => $conf) {
            
            if (isset($existent[$key])) {
                $settings[$key] = $this->patchEntity($existent[$key], $conf);
            } else {
                $settings[$key] = $this->newEntity(array_merge([
                        'type' => 'string',
                        'scope' => $plugin,
                        'key' => $key,
                        'default' => null,
                        'value' => null,
                    ], $conf));
            }
            
        }

        //@TODO Mark obsolete settings
        foreach ($settings as $setting) {
            if (!$this->save($setting)) {
                debug($setting->errors());
                return false;
            }
        }

        return true;
    }
    */

    public function getCompiled($scope = 'global')
    {
        $settings = $this->find()
            //->where(['Settings.scope IS' => $scope])
            ->order(['Settings.key' => 'ASC'])
            ->all()
            ->toArray();

        $compiled = [];
        foreach ($settings as $setting) {
            $compiled[$setting->key] = ($setting->value) ?: $setting->default;
        }

        return $compiled;
    }
}
