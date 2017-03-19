<?php

namespace Banana\Model;

use Cake\Collection\Collection;
use Cake\Collection\Iterator\FilterIterator;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\RepositoryInterface;

/**
 * Class ArrayTable
 * @package Banana\Model
 *
 * @todo Cleanup annotations
 */
abstract class ArrayTable implements RepositoryInterface
{
    protected $_displayField = 'title';

    /**
     * Required to work with TableRegistry
     * This static method is called by the TableLocator.
     *
     * @return string
     */
    static public function defaultConnectionName()
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

    /**
     * Create new ArrayTableQuery object
     *
     * @return ArrayTableQuery
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
     * @return Collection
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
        $class = get_class($this);
        return substr($class, 0, -strlen('Table'));
    }

    /**
     * Test to see if a Repository has a specific field/column.
     *
     * @param string $field The field to check for.
     * @return bool True if the field exists, false if it does not.
     */
    public function hasField($field)
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
     * @param ArrayTableQuery $query
     * @param array $options
     * @return ArrayTableQuery|mixed
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
     * @param ArrayTableQuery $query
     * @param array $options
     * @return ArrayTableQuery
     */
    public function findAll(ArrayTableQuery $query, array $options)
    {
        return $query;
    }

    /**
     * @param ArrayTableQuery $query
     * @param array $options
     * @return $this|array
     */
    public function findList(ArrayTableQuery $query, array $options = [])
    {
        $options = array_merge([
            'valueField' => $this->displayField()
        ],$options);

        return $query->formatResults(function (Collection $results) use ($options) {
            $list = [];
            $results->each(function($result, $key) use (&$list, &$options){
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
    public function get($primaryKey, $options = [])
    {
        $result = $this->getCollection()->filter(function($item, $key) use ($primaryKey) {
            if ($key == $primaryKey) {
                return true;
            }
        });

        $entity = $result->first();
        if (!$entity) {
            throw new RecordNotFoundException($primaryKey);
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
    public function updateAll($fields, $conditions)
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
    public function deleteAll($conditions)
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
    public function exists($conditions)
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
    public function delete(EntityInterface $entity, $options = [])
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
     * $article = $this->Articles->newEntity($this->request->data());
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
    public function newEntity($data = null, array $options = [])
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
     * $articles = $this->Articles->newEntities($this->request->data());
     * ```
     *
     * The hydrated entities can then be iterated and saved.
     *
     * @param array $data The data to build an entity with.
     * @param array $options A list of options for the objects hydration.
     * @return array An array of hydrated records.
     */
    public function newEntities(array $data, array $options = [])
    {
        $entities = [];
        foreach ($data as $row) {
            $entities[] = new ArrayTableEntity($row);
        }
        return $entities;
    }

    /**
     * Merges the passed `$data` into `$entity` respecting the accessible
     * fields configured on the entity. Returns the same entity after being
     * altered.
     *
     * This is most useful when editing an existing entity using request data:
     *
     * ```
     * $article = $this->Articles->patchEntity($article, $this->request->data());
     * ```
     *
     * @param \Cake\Datasource\EntityInterface $entity the entity that will get the
     * data merged in
     * @param array $data key value list of fields to be merged into the entity
     * @param array $options A list of options for the object hydration.
     * @return \Cake\Datasource\EntityInterface
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = [])
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
     * $article = $this->Articles->patchEntities($articles, $this->request->data());
     * ```
     *
     * @param array|\Traversable $entities the entities that will get the
     * data merged in
     * @param array $data list of arrays to be merged into the entities
     * @param array $options A list of options for the objects hydration.
     * @return array
     */
    public function patchEntities($entities, array $data, array $options = [])
    {
        // TODO: Implement patchEntities() method.
        return $entities;
    }
}