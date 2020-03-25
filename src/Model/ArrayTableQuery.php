<?php
declare(strict_types=1);

namespace Banana\Model;

use BadMethodCallException;
use Cake\Datasource\QueryInterface;
use Cake\Datasource\QueryTrait;
use Cake\Datasource\RepositoryInterface;

/**
 * Class ArrayTableQuery
 *
 * @package Banana\Model
 */
class ArrayTableQuery implements QueryInterface
{
    use QueryTrait;

    /**
     * Indicates that the operation should append to the list
     *
     * @var int
     */
    public const APPEND = 0;

    /**
     * Indicates that the operation should prepend to the list
     *
     * @var int
     */
    public const PREPEND = 1;

    /**
     * Indicates that the operation should overwrite the list
     *
     * @var bool
     */
    public const OVERWRITE = true;

    /**
     * @var array|callable
     */
    protected $_conditions;

    /**
     * @var \Banana\Model\ArrayTable
     */
    //protected $_repository = null;

    /**
     * @param \Banana\Model\ArrayTable $table
     */
    public function __construct(ArrayTable $table)
    {
        $this->repository($table);
    }

    /**
     * Returns a key => value array representing a single aliased field
     * that can be passed directly to the select() method.
     * The key will contain the alias and the value the actual field name.
     *
     * If the field is already aliased, then it will not be changed.
     * If no $alias is passed, the default table for this query will be used.
     *
     * @param string $field The field to alias
     * @param string|null $alias the alias used to prefix the field
     * @return array
     */
    public function aliasField($field, $alias = null)
    {
        return $field;
    }

    /**
     * Runs `aliasField()` for each field in the provided list and returns
     * the result under a single array.
     *
     * @param array $fields The fields to alias
     * @param string|null $defaultAlias The default alias
     * @return array
     */
    public function aliasFields($fields, $defaultAlias = null)
    {
        return $fields;
    }

    /**
     * Populates or adds parts to current query clauses using an array.
     * This is handy for passing all query clauses at once. The option array accepts:
     *
     * - fields: Maps to the select method
     * - conditions: Maps to the where method
     * - limit: Maps to the limit method
     * - order: Maps to the order method
     * - offset: Maps to the offset method
     * - group: Maps to the group method
     * - having: Maps to the having method
     * - contain: Maps to the contain options for eager loading
     * - join: Maps to the join method
     * - page: Maps to the page method
     *
     * ### Example:
     *
     * ```
     * $query->applyOptions([
     *   'fields' => ['id', 'name'],
     *   'conditions' => [
     *     'created >=' => '2013-01-01'
     *   ],
     *   'limit' => 10
     * ]);
     * ```
     *
     * Is equivalent to:
     *
     * ```
     *  $query
     *  ->select(['id', 'name'])
     *  ->where(['created >=' => '2013-01-01'])
     *  ->limit(10)
     * ```
     *
     * @param array $options list of query clauses to apply new parts to.
     * @return $this
     */
    public function applyOptions(array $options)
    {
        return $this;
    }

    /**
     * Apply custom finds to against an existing query object.
     *
     * Allows custom find methods to be combined and applied to each other.
     *
     * ```
     * $repository->find('all')->find('recent');
     * ```
     *
     * The above is an example of stacking multiple finder methods onto
     * a single query.
     *
     * @param string $finder The finder method to use.
     * @param array $options The options for the finder.
     * @return $this Returns a modified query.
     */
    public function find($finder, array $options = [])
    {
        return $this->getRepository()->callFinder($finder, $this, $options);
    }

    /**
     * Returns the total amount of results for the query.
     *
     * @return int
     */
    public function count()
    {
        // TODO: Implement count() method.
        return count($this->getRepository()->getItems());
    }

    /**
     * Sets the number of records that should be retrieved from database,
     * accepts an integer or an expression object that evaluates to an integer.
     * In some databases, this operation might not be supported or will require
     * the query to be transformed in order to limit the result set size.
     *
     * ### Examples
     *
     * ```
     * $query->limit(10) // generates LIMIT 10
     * $query->limit($query->newExpr()->add(['1 + 1'])); // LIMIT (1 + 1)
     * ```
     *
     * @param int $num number of records to be returned
     * @return $this
     */
    public function limit($num)
    {
        // TODO: Implement limit() method.
        return $this;
    }

    /**
     * Sets the number of records that should be skipped from the original result set
     * This is commonly used for paginating large results. Accepts an integer or an
     * expression object that evaluates to an integer.
     *
     * In some databases, this operation might not be supported or will require
     * the query to be transformed in order to limit the result set size.
     *
     * ### Examples
     *
     * ```
     *  $query->offset(10) // generates OFFSET 10
     *  $query->offset($query->newExpr()->add(['1 + 1'])); // OFFSET (1 + 1)
     * ```
     *
     * @param int $num number of records to be skipped
     * @return $this
     */
    public function offset($num)
    {
        // TODO: Implement offset() method.
        return $this;
    }

    /**
     * Adds a single or multiple fields to be used in the ORDER clause for this query.
     * Fields can be passed as an array of strings, array of expression
     * objects, a single expression or a single string.
     *
     * If an array is passed, keys will be used as the field itself and the value will
     * represent the order in which such field should be ordered. When called multiple
     * times with the same fields as key, the last order definition will prevail over
     * the others.
     *
     * By default this function will append any passed argument to the list of fields
     * to be selected, unless the second argument is set to true.
     *
     * ### Examples:
     *
     * ```
     * $query->order(['title' => 'DESC', 'author_id' => 'ASC']);
     * ```
     *
     * Produces:
     *
     * `ORDER BY title DESC, author_id ASC`
     *
     * ```
     * $query->order(['title' => 'DESC NULLS FIRST'])->order('author_id');
     * ```
     *
     * Will generate:
     *
     * `ORDER BY title DESC NULLS FIRST, author_id`
     *
     * ```
     * $expression = $query->newExpr()->add(['id % 2 = 0']);
     * $query->order($expression)->order(['title' => 'ASC']);
     * ```
     *
     * Will become:
     *
     * `ORDER BY (id %2 = 0), title ASC`
     *
     * If you need to set complex expressions as order conditions, you
     * should use `orderAsc()` or `orderDesc()`.
     *
     * @param array|string $fields fields to be added to the list
     * @param bool $overwrite whether to reset order with field list or not
     * @return $this
     */
    public function order($fields, $overwrite = false)
    {
        // TODO: Implement order() method.
        return $this;
    }

    /**
     * Set the page of results you want.
     *
     * This method provides an easier to use interface to set the limit + offset
     * in the record set you want as results. If empty the limit will default to
     * the existing limit clause, and if that too is empty, then `25` will be used.
     *
     * Pages should start at 1.
     *
     * @param int $num The page number you want.
     * @param int|null $limit The number of rows you want in the page. If null
     *  the current limit clause will be used.
     * @return $this
     */
    public function page($num, $limit = null)
    {
        // TODO: Implement page() method.
        return $this;
    }

    /**
     * Returns the default repository object that will be used by this query,
     * that is, the repository that will appear in the from clause.
     *
     * @param \Cake\Datasource\RepositoryInterface|null $repository The default repository object to use
     * @return \Banana\Model\ArrayTable|$this
     */
    public function repository(?RepositoryInterface $repository = null)
    {
        if ($repository === null) {
            return $this->_repository;
        }

        $this->_repository = $repository;

        return $this;
    }

    /**
     * Adds a condition or set of conditions to be used in the WHERE clause for this
     * query. Conditions can be expressed as an array of fields as keys with
     * comparison operators in it, the values for the array will be used for comparing
     * the field to such literal. Finally, conditions can be expressed as a single
     * string or an array of strings.
     *
     * When using arrays, each entry will be joined to the rest of the conditions using
     * an AND operator. Consecutive calls to this function will also join the new
     * conditions specified using the AND operator. Additionally, values can be
     * expressed using expression objects which can include other query objects.
     *
     * Any conditions created with this methods can be used with any SELECT, UPDATE
     * and DELETE type of queries.
     *
     * ### Conditions using operators:
     *
     * ```
     *  $query->where([
     *      'posted >=' => new DateTime('3 days ago'),
     *      'title LIKE' => 'Hello W%',
     *      'author_id' => 1,
     *  ], ['posted' => 'datetime']);
     * ```
     *
     * The previous example produces:
     *
     * `WHERE posted >= 2012-01-27 AND title LIKE 'Hello W%' AND author_id = 1`
     *
     * Second parameter is used to specify what type is expected for each passed
     * key. Valid types can be used from the mapped with Database\Type class.
     *
     * ### Nesting conditions with conjunctions:
     *
     * ```
     *  $query->where([
     *      'author_id !=' => 1,
     *      'OR' => ['published' => true, 'posted <' => new DateTime('now')],
     *      'NOT' => ['title' => 'Hello']
     *  ], ['published' => boolean, 'posted' => 'datetime']
     * ```
     *
     * The previous example produces:
     *
     * `WHERE author_id = 1 AND (published = 1 OR posted < '2012-02-01') AND NOT (title = 'Hello')`
     *
     * You can nest conditions using conjunctions as much as you like. Sometimes, you
     * may want to define 2 different options for the same key, in that case, you can
     * wrap each condition inside a new array:
     *
     * `$query->where(['OR' => [['published' => false], ['published' => true]])`
     *
     * Keep in mind that every time you call where() with the third param set to false
     * (default), it will join the passed conditions to the previous stored list using
     * the AND operator. Also, using the same array key twice in consecutive calls to
     * this method will not override the previous value.
     *
     * ### Using expressions objects:
     *
     * ```
     *  $exp = $query->newExpr()->add(['id !=' => 100, 'author_id' != 1])->tieWith('OR');
     *  $query->where(['published' => true], ['published' => 'boolean'])->where($exp);
     * ```
     *
     * The previous example produces:
     *
     * `WHERE (id != 100 OR author_id != 1) AND published = 1`
     *
     * Other Query objects that be used as conditions for any field.
     *
     * ### Adding conditions in multiple steps:
     *
     * You can use callable functions to construct complex expressions, functions
     * receive as first argument a new QueryExpression object and this query instance
     * as second argument. Functions must return an expression object, that will be
     * added the list of conditions for the query using the AND operator.
     *
     * ```
     *  $query
     *  ->where(['title !=' => 'Hello World'])
     *  ->where(function ($exp, $query) {
     *      $or = $exp->or_(['id' => 1]);
     *      $and = $exp->and_(['id >' => 2, 'id <' => 10]);
     *  return $or->add($and);
     *  });
     * ```
     *
     * * The previous example produces:
     *
     * `WHERE title != 'Hello World' AND (id = 1 OR (id > 2 AND id < 10))`
     *
     * ### Conditions as strings:
     *
     * ```
     *  $query->where(['articles.author_id = authors.id', 'modified IS NULL']);
     * ```
     *
     * The previous example produces:
     *
     * `WHERE articles.author_id = authors.id AND modified IS NULL`
     *
     * Please note that when using the array notation or the expression objects, all
     * values will be correctly quoted and transformed to the correspondent database
     * data type automatically for you, thus securing your application from SQL injections.
     * If you use string conditions make sure that your values are correctly quoted.
     * The safest thing you can do is to never use string conditions.
     *
     * @param string|array|callable|null $conditions The conditions to filter on.
     * @param array $types associative array of type names used to bind values to query
     * @param bool $overwrite whether to reset conditions with passed list or not
     * @return $this
     */
    public function where($conditions = null, $types = [], $overwrite = false)
    {
        if (!is_array($conditions) && !is_callable($conditions)) {
            throw new BadMethodCallException("Condition MUST be expressed as array or callable");
        }
        $this->_conditions = $conditions;

        return $this;
    }

    /**
     * Executes this query and returns a traversable object containing the results
     *
     * @return \Traversable
     */
    protected function _execute()
    {
        $items = $this->_repository->getItems();
        $results = new ArrayTableResultSet((array)$items);

        if (is_array($this->_conditions) && !empty($this->_conditions)) {
            $results = $results->filter(function ($v, $k) {
                foreach ($this->_conditions as $_f => $_v) {
                    if (!array_key_exists($_f, $v) || $v[$_f] != $_v) {
                        return false;
                    }
                }

                return true;
            });
        } elseif (is_callable($this->_conditions)) {
            $results = $results->filter($this->_conditions);
        }

        return $results;
    }
}
