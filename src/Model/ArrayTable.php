<?php
declare(strict_types=1);

namespace Banana\Model;

use Cake\Collection\Collection;
use Cake\Database\Schema\TableSchema as Schema;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\RepositoryInterface;
use Cake\ORM\AssociationCollection;
use Cake\ORM\BehaviorRegistry;

/**
 * Class ArrayTable
 * @package Banana\Model
 *
 * @todo Cleanup annotations
 */
abstract class ArrayTable implements RepositoryInterface
{
    /**
     * @var string
     */
    protected $_displayField = 'title';

    /**
     * @var \Cake\Database\Schema\TableSchema
     */
    protected $_schema;

    /**
     * @var \Cake\ORM\BehaviorRegistry
     */
    protected $_behaviors;

    /**
     * @var array
     */
    protected $_config;

    /**
     * Required to work with TableRegistry
     * This static method is called by the TableLocator.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'default';
    }

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (isset($config['displayField'])) {
            $this->displayField($config['displayField']);
        }

        $this->_config = $config;

        $this->_behaviors = new BehaviorRegistry();
        $this->_behaviors->getEventManager()->unsetEventList();

        $this->initialize();
    }

    /**
     * Custom table initializer method
     */
    public function initialize()
    {
        // Override in subclasses
    }

    /**
     * Returns the schema table object describing this table's properties.
     *
     * If an \Cake\Database\Schema\TableSchema is passed, it will be used for this table
     * instead of the default one.
     *
     * If an array is passed, a new \Cake\Database\Schema\TableSchema will be constructed
     * out of it and used as the schema for this table.
     *
     * @param array|\Cake\Database\Schema\TableSchema|null $schema New schema to be used for this table
     * @return \Cake\Database\Schema\TableSchema
     */
    public function schema($schema = null)
    {
        if ($schema === null) {
            if ($this->_schema === null) {
                $schema = new Schema($this->getAlias(), []);
                $this->_schema = $this->_initializeSchema($schema);
            }

            return $this->_schema;
        }

        /*
        if (is_array($schema)) {
            $constraints = [];

            if (isset($schema['_constraints'])) {
                $constraints = $schema['_constraints'];
                unset($schema['_constraints']);
            }

            $schema = new Schema($this->getAlias(), $schema);

            foreach ($constraints as $name => $value) {
                $schema->addConstraint($name, $value);
            }
        }
       */

        return $this->_schema = $schema;
    }

    /**
     * Override this function in order to alter the schema used by this table.
     * This function is only called after fetching the schema out of the database.
     * If you wish to provide your own schema to this table without touching the
     * database, you can override getSchema() or inject the definitions though that
     * method.
     *
     * ### Example:
     *
     * ```
     * protected function _initializeSchema(\Cake\Database\Schema\TableSchema $table) {
     *  $table->setColumnType('preferences', 'json');
     *  return $table;
     * }
     * ```
     *
     * @param \Cake\Database\Schema\TableSchema $table The table definition fetched from database.
     * @return \Cake\Database\Schema\TableSchema The altered schema.
     */
    protected function _initializeSchema(Schema $table)
    {
        if (isset($this->_config['schema'])) {
            foreach ($this->_config['schema'] as $column => $attrs) {
                $table->addColumn($column, $attrs);
            }
        }

        return $table;
    }

    /**
     * Get / Set display field
     *
     * @param null $field
     * @return $this|string
     */
    public function displayField($field = null)
    {
        if ($field === null) {
            return $this->_displayField;
        }

        $this->_displayField = $field;

        return $this;
    }

    public function getDisplayField()
    {
        return $this->_displayField;
    }

    public function setDisplayField($field)
    {
        $this->_displayField = $field;

        return $this;
    }

    /**
     * Create new ArrayTableQuery object
     *
     * @return \Banana\Model\ArrayTableQuery
     */
    public function query()
    {
        return new ArrayTableQuery($this);
    }

    /**
     * Return array table data
     *
     * @return array
     */
    abstract public function getItems();

    /**
     * Return array table data as collection
     *
     * @return \Cake\Collection\Collection
     */
    public function getCollection()
    {
        return new Collection($this->getItems());
    }

    /**
     * Returns the table alias or sets a new one
     *
     * @param string|null $alias the new table alias
     * @return string
     */
    public function alias($alias = null)
    {
        $class = static::class;

        return substr($class, 0, -strlen('Table'));
    }

    public function table()
    {
        return $this->getAlias();
    }

    /**
     * Test to see if a Repository has a specific field/column.
     *
     * @param string $field The field to check for.
     * @return bool True if the field exists, false if it does not.
     */
    public function hasField($field): bool
    {
        //@TODO Schema for ArrayTables
        return true;
    }

    /**
     * Creates a new Query for this repository and applies some defaults based on the
     * type of search that was selected.
     *
     * ### Model.beforeFind event
     *
     * Each find() will trigger a `Model.beforeFind` event for all attached
     * listeners. Any listener can set a valid result set using $query
     *
     * @param string $type the type of query to perform
     * @param array|\ArrayAccess $options An array that will be passed to Query::applyOptions()
     * @return \Cake\ORM\Query
     */
    public function find($type = 'all', $options = [])
    {
        $query = $this->query();

        return $this->callFinder($type, $query, $options);
    }

    /**
     * @param $type
     * @param \Banana\Model\ArrayTableQuery $query
     * @param array $options
     * @return \Banana\Model\ArrayTableQuery|mixed
     */
    public function callFinder($type, ArrayTableQuery $query, array $options = [])
    {
        $finder = 'find' . ucfirst($type);
        if (method_exists($this, $finder)) {
            return call_user_func([$this, $finder], $query, $options);
        }

        return $this->query();
    }

    /**
     * @param \Banana\Model\ArrayTableQuery $query
     * @param array $options
     * @return \Banana\Model\ArrayTableQuery
     */
    public function findAll(ArrayTableQuery $query, array $options)
    {
        return $query;
    }

    /**
     * @param \Banana\Model\ArrayTableQuery $query
     * @param array $options
     * @return $this|array
     */
    public function findList(ArrayTableQuery $query, array $options = [])
    {
        $options = array_merge([
            'valueField' => $this->getDisplayField(),
        ], $options);

        return $query->formatResults(function (Collection $results) use ($options) {
            $list = [];
            $results->each(function ($result, $key) use (&$list, &$options) {
                $list[$key] = $result[$options['valueField']];
            });

            return new ArrayTableResultSet($list);
        }, ArrayTableQuery::OVERWRITE);
    }

    /**
     * Returns a single record after finding it by its primary key, if no record is
     * found this method throws an exception.
     *
     * ### Example:
     *
     * ```
     * $id = 10;
     * $article = $articles->get($id);
     *
     * $article = $articles->get($id, ['contain' => ['Comments]]);
     * ```
     *
     * @param mixed $primaryKey primary key value to find
     * @param array|\ArrayAccess $options options accepted by `Table::find()`
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if the record with such id
     * could not be found
     * @return \Cake\Datasource\EntityInterface
     * @see \Cake\Datasource\RepositoryInterface::find()
     */
    public function get($primaryKey, array $options = []): EntityInterface
    {
        $result = $this->getCollection()->filter(function ($item, $key) use ($primaryKey) {
            if ($key == $primaryKey) {
                return true;
            }
        });

        $entity = $result->first();
        if (!$entity) {
            throw new InvalidPrimaryKeyException(sprintf(
                'Record not found in array table "%s" with primary key [%s]',
                $this->table(),
                $primaryKey
            ));
        }

        return $this->newEntity($entity);
    }

    /**
     * Update all matching records.
     *
     * Sets the $fields to the provided values based on $conditions.
     * This method will *not* trigger beforeSave/afterSave events. If you need those
     * first load a collection of records and update them.
     *
     * @param string|array|callable|\Cake\Database\Expression\QueryExpression $fields A hash of field => new value.
     * @param mixed $conditions Conditions to be used, accepts anything Query::where()
     * can take.
     * @return int Count Returns the affected rows.
     */
    public function updateAll($fields, $conditions): int
    {
        //@TODO Implement updateAll() method
        return 0;
    }

    /**
     * Delete all matching records.
     *
     * Deletes all records matching the provided conditions.
     *
     * This method will *not* trigger beforeDelete/afterDelete events. If you
     * need those first load a collection of records and delete them.
     *
     * This method will *not* execute on associations' `cascade` attribute. You should
     * use database foreign keys + ON CASCADE rules if you need cascading deletes combined
     * with this method.
     *
     * @param mixed $conditions Conditions to be used, accepts anything Query::where()
     * can take.
     * @return int Count Returns the affected rows.
     * @see \Cake\Datasource\RepositoryInterface::delete()
     */
    public function deleteAll($conditions): int
    {
        //@TODO Implement deleteAll() method
        return 0;
    }

    /**
     * Returns true if there is any record in this repository matching the specified
     * conditions.
     *
     * @param array|\ArrayAccess $conditions list of conditions to pass to the query
     * @return bool
     */
    public function exists($conditions): bool
    {
        //@TODO Implement exists() method
        return true;
    }

    /**
     * Persists an entity based on the fields that are marked as dirty and
     * returns the same entity after a successful save or false in case
     * of any error.
     *
     * @param \Cake\Datasource\EntityInterface $entity the entity to be saved
     * @param array|\ArrayAccess $options The options to use when saving.
     * @return \Cake\Datasource\EntityInterface|bool
     */
    public function save(EntityInterface $entity, $options = [])
    {
        //@TODO Implement save() method
        return false;
    }

    /**
     * Delete a single entity.
     *
     * Deletes an entity and possibly related associations from the database
     * based on the 'dependent' option used when defining the association.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to remove.
     * @param array|\ArrayAccess $options The options for the delete.
     * @return bool success
     */
    public function delete(EntityInterface $entity, $options = []): bool
    {
        //@TODO Implement delete() method
        return false;
    }

    /**
     * Create a new entity + associated entities from an array.
     *
     * This is most useful when hydrating request data back into entities.
     * For example, in your controller code:
     *
     * ```
     * $article = $this->Articles->newEntity($this->request->getData());
     * ```
     *
     * The hydrated entity will correctly do an insert/update based
     * on the primary key data existing in the database when the entity
     * is saved. Until the entity is saved, it will be a detached record.
     *
     * @param array|null $data The data to build an entity with.
     * @param array $options A list of options for the object hydration.
     * @return \Cake\Datasource\EntityInterface
     */
    public function newEntity(array $data, array $options = []): EntityInterface
    {
        if ($data === null) {
            $data = [];
        }

        return new ArrayTableEntity($data);
    }

    /**
     * Create a list of entities + associated entities from an array.
     *
     * This is most useful when hydrating request data back into entities.
     * For example, in your controller code:
     *
     * ```
     * $articles = $this->Articles->newEntities($this->request->getData());
     * ```
     *
     * The hydrated entities can then be iterated and saved.
     *
     * @param array $data The data to build an entity with.
     * @param array $options A list of options for the objects hydration.
     * @return array An array of hydrated records.
     */
    public function newEntities(array $data, array $options = []): array
    {
        $entities = [];
        foreach ($data as $row) {
            $entities[] = new ArrayTableEntity($row);
        }

        return $entities;
    }

    /**
     * @inheritDoc
     */
    public function newEmptyEntity(): EntityInterface
    {
        // TODO: Implement newEmptyEntity() method.
    }

    /**
     * Merges the passed `$data` into `$entity` respecting the accessible
     * fields configured on the entity. Returns the same entity after being
     * altered.
     *
     * This is most useful when editing an existing entity using request data:
     *
     * ```
     * $article = $this->Articles->patchEntity($article, $this->request->getData());
     * ```
     *
     * @param \Cake\Datasource\EntityInterface $entity the entity that will get the
     * data merged in
     * @param array $data key value list of fields to be merged into the entity
     * @param array $options A list of options for the object hydration.
     * @return \Cake\Datasource\EntityInterface
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = []): EntityInterface
    {
        $entity->set($data);

        return $entity;
    }

    /**
     * Merges each of the elements passed in `$data` into the entities
     * found in `$entities` respecting the accessible fields configured on the entities.
     * Merging is done by matching the primary key in each of the elements in `$data`
     * and `$entities`.
     *
     * This is most useful when editing a list of existing entities using request data:
     *
     * ```
     * $article = $this->Articles->patchEntities($articles, $this->request->getData());
     * ```
     *
     * @param array|\Traversable $entities the entities that will get the
     * data merged in
     * @param array $data list of arrays to be merged into the entities
     * @param array $options A list of options for the objects hydration.
     * @return array
     */
    public function patchEntities(iterable $entities, array $data, array $options = []): array
    {
        // TODO: Implement patchEntities() method.
        return $entities;
    }

    /**
     * @return \Cake\ORM\BehaviorRegistry
     */
    public function behaviors()
    {
        return $this->_behaviors;
    }

    /**
     * Get the associations collection for this table.
     *
     * @return \Cake\ORM\AssociationCollection The collection of association objects.
     */
    public function associations()
    {
        return new AssociationCollection();
    }

    function __call($name, $arguments)
    {
        // TODO: Implement @method $this setAlias(string $alias)
        // TODO: Implement @method string getAlias()
        // TODO: Implement @method $this setRegistryAlias(string $alias)
        // TODO: Implement @method string getRegistryAlias()
    }

    /**
     * @return $this
     */
    public function setAlias(string $alias)
    {
        $this->alias($alias);

        return $this;
    }

    public function getAlias(): string
    {
        return $this->alias();
    }

    /**
     * @return $this
     */
    public function setRegistryAlias(string $alias)
    {
        $this->alias($alias);

        return $this;
    }

    public function getRegistryAlias(): string
    {
        return $this->getAlias();
    }
}
