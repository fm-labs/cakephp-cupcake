<?php

namespace Banana\Test\Model\Behavior;


use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use DebugKit\Database\Log\DebugLog;

/**
 * @property \Cake\ORM\Table $table
 */
class SortableBehaviorTest extends TestCase
{
    public $fixtures = [
        'plugin.banana.posts'
    ];

    /**
     * @var DebugLog
     */
    public $dbLogger;

    public function setUp()
    {
        parent::setUp();
        $this->table = TableRegistry::get('Banana.Posts');
        $this->table->primaryKey(['id']);
        if ($this->table->behaviors()->has('Sortable')) {
            $this->table->behaviors()->unload('Sortable');
        }
        $this->table->addBehavior('Banana.Sortable', ['scope' => ['refscope', 'refid']]);

        $this->_setupDbLogging();
    }


    protected function _setupDbLogging()
    {

        $connection = ConnectionManager::get('test');

        $logger = $connection->logger();
        $this->dbLogger = new DebugLog($logger, 'test');

        $connection->logQueries(true);
        $connection->logger($this->dbLogger);
    }

    public function tearDown()
    {
        parent::tearDown();
        TableRegistry::clear();
    }

    public function testFindSorted()
    {
        $this->table->behaviors()->unload('Sortable');
        $this->table->addBehavior('Banana.Sortable', ['scope' => []]);
        $sorted = $this->table->find('sorted')->select(['id', 'title', 'pos'])->hydrate(false)->all();
        //debug($sorted->toArray());
    }

    public function testFindSortedScoped()
    {
        $sorted = $this->table->find('sorted')->select(['id', 'title', 'pos'])->hydrate(false)->all();
        //debug($sorted->toArray());
    }

    public function testMoveUp()
    {
        $entity = $this->table->moveUp($this->table->get(3));
        $this->assertEquals(2, $entity->pos);

        $this->assertEquals(1, $this->table->get(1)->pos);
        $this->assertEquals(2, $this->table->get(3)->pos);
        $this->assertEquals(3, $this->table->get(2)->pos);
        $this->assertEquals(4, $this->table->get(4)->pos);
    }

    public function testMoveDown()
    {
        $entity = $this->table->moveDown($this->table->get(2));
        $this->assertEquals(3, $entity->pos);

        $this->assertEquals(1, $this->table->get(1)->pos);
        $this->assertEquals(2, $this->table->get(3)->pos);
        $this->assertEquals(3, $this->table->get(2)->pos);
        $this->assertEquals(4, $this->table->get(4)->pos);
    }

    public function testMoveTop()
    {
        $entity = $this->table->moveTop($this->table->get(3));
        //debug($this->dbLogger->queries());
        $this->assertEquals(1, $entity->pos);

        $this->assertEquals(1, $this->table->get(3)->pos);
        $this->assertEquals(2, $this->table->get(1)->pos);
        $this->assertEquals(3, $this->table->get(2)->pos);
        $this->assertEquals(4, $this->table->get(4)->pos);
    }

    public function testMoveTopNodeToTop()
    {
        $entity = $this->table->moveTop($this->table->get(1));
        //debug($this->dbLogger->queries());
        $this->assertEquals(1, $entity->pos);
    }

    public function testMoveBottomNodeToTop()
    {
        $entity = $this->table->moveTop($this->table->get(4));
        //debug($this->dbLogger->queries());
        $this->assertEquals(1, $entity->pos);

        $this->assertEquals(1, $this->table->get(4)->pos);
        $this->assertEquals(2, $this->table->get(1)->pos);
        $this->assertEquals(3, $this->table->get(2)->pos);
        $this->assertEquals(4, $this->table->get(3)->pos);
    }

    public function testMoveBottom()
    {
        $entity = $this->table->moveBottom($this->table->get(2));
        //debug($this->dbLogger->queries());
        $this->assertEquals(4, $entity->pos);

        $this->assertEquals(1, $this->table->get(1)->pos);
        $this->assertEquals(2, $this->table->get(3)->pos);
        $this->assertEquals(3, $this->table->get(4)->pos);
        $this->assertEquals(4, $this->table->get(2)->pos);
    }

    public function testMoveAfter()
    {
        $entity = $this->table->moveAfter($this->table->get(2), 3);
        //debug($this->dbLogger->queries());
        $this->assertEquals(3, $entity->pos);

        $this->assertEquals(1, $this->table->get(1)->pos);
        $this->assertEquals(2, $this->table->get(3)->pos);
        $this->assertEquals(3, $this->table->get(2)->pos);
        $this->assertEquals(4, $this->table->get(4)->pos);
    }

    public function testMoveAfterLast()
    {
        $entity = $this->table->moveAfter($this->table->get(2), 4);
        //debug($this->dbLogger->queries());
        $this->assertEquals(4, $entity->pos);

        $this->assertEquals(1, $this->table->get(1)->pos);
        $this->assertEquals(2, $this->table->get(3)->pos);
        $this->assertEquals(3, $this->table->get(4)->pos);
        $this->assertEquals(4, $this->table->get(2)->pos);
    }

    public function testMoveFirstAfterLast()
    {
        $entity = $this->table->moveAfter($this->table->get(1), 4);
        //debug($this->dbLogger->queries());
        $this->assertEquals(4, $entity->pos);

        $this->assertEquals(1, $this->table->get(2)->pos);
        $this->assertEquals(2, $this->table->get(3)->pos);
        $this->assertEquals(3, $this->table->get(4)->pos);
        $this->assertEquals(4, $this->table->get(1)->pos);
    }

    public function testMoveAfterOutOfBounds()
    {
        $entity = $this->table->moveAfter($this->table->get(1), 4);
        //debug($this->dbLogger->queries());
        $this->assertEquals(4, $entity->pos);

        $this->assertEquals(1, $this->table->get(2)->pos);
        $this->assertEquals(2, $this->table->get(3)->pos);
        $this->assertEquals(3, $this->table->get(4)->pos);
        $this->assertEquals(4, $this->table->get(1)->pos);
    }

    public function testMoveAfterSelf()
    {
        $entity = $this->table->moveAfter($this->table->get(1), 1);
        //debug($this->dbLogger->queries());
        $this->assertEquals(1, $entity->pos);
    }

    public function testMoveBefore()
    {
        $entity = $this->table->moveBefore($this->table->get(4), 2);
        //debug($this->dbLogger->queries());
        $this->assertEquals(2, $entity->pos);

        $this->assertEquals(1, $this->table->get(1)->pos);
        $this->assertEquals(2, $this->table->get(4)->pos);
        $this->assertEquals(3, $this->table->get(2)->pos);
        $this->assertEquals(4, $this->table->get(3)->pos);
    }

    public function testMoveBeforeFirst()
    {
        $entity = $this->table->moveBefore($this->table->get(4), 1);
        //debug($this->dbLogger->queries());
        $this->assertEquals(1, $entity->pos);

        $this->assertEquals(1, $this->table->get(4)->pos);
        $this->assertEquals(2, $this->table->get(1)->pos);
        $this->assertEquals(3, $this->table->get(2)->pos);
        $this->assertEquals(4, $this->table->get(3)->pos);
    }

    public function testMoveBeforeLast()
    {
        $entity = $this->table->moveBefore($this->table->get(1), 4);
        //debug($this->dbLogger->queries());
        $this->assertEquals(3, $entity->pos);

        $this->assertEquals(1, $this->table->get(2)->pos);
        $this->assertEquals(2, $this->table->get(3)->pos);
        $this->assertEquals(3, $this->table->get(1)->pos);
        $this->assertEquals(4, $this->table->get(4)->pos);
    }
}