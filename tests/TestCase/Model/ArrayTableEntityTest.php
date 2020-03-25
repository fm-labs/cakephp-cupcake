<?php
declare(strict_types=1);

namespace Banana\Test\TestCase\Model;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Class ArrayTableEntityTest
 * @package Banana\Test\TestCase\Model
 */
class ArrayTableEntityTest extends TestCase
{
    /**
     * @var ArrayTable
     */
    public $Table;

    /**
     * @var array
     */
    public $testData = [
        'title' => 'Test',
        'foo' => 'bar',
        'hello' => 'world',
        'boolean' => true,
        'int' => 1,
    ];

    /**
     * Setup test
     */
    public function setUp(): void
    {
        TableRegistry::getTableLocator()->setConfig('TestArray', [
            'className' => 'Banana\Test\TestCase\Model\Table\TestArrayTable',
        ]);
        $this->Table = TableRegistry::getTableLocator()->get('TestArray');
    }

    public function testNewEntity()
    {
        $entity = $this->Table->newEntity();

        $this->assertInstanceOf('\ArrayObject', $entity);
        $this->assertInstanceOf('\ArrayAccess', $entity);
        $this->assertInstanceOf('\Cake\Datasource\EntityInterface', $entity);
        $this->assertInstanceOf('\Banana\Model\ArrayTableEntity', $entity);
    }

    public function testPropertyGetter()
    {
        $entity = $this->Table->newEntity($this->testData);

        $this->assertEquals('Test', $entity->get('title'));
    }

    public function testMagicPropertyGetter()
    {
        $entity = $this->Table->newEntity($this->testData);

        $this->assertEquals('Test', $entity->title);
    }

    public function testPropertySetter()
    {
        $entity = $this->Table->newEntity($this->testData);
        $entity->set('title', 'Foo');

        $this->assertEquals('Foo', $entity->get('title'));
    }

    public function testMagicPropertySetter()
    {
        $entity = $this->Table->newEntity($this->testData);
        $entity->title = 'Foo';

        $this->assertEquals('Foo', $entity->get('title'));
    }

    public function testPropertyGetterWithAccessor()
    {
        $this->markTestIncomplete();
    }

    public function testPatchEntity()
    {
        $this->markTestIncomplete();
    }

    public function testPatchEntityWithNonAccessibleFields()
    {
        $this->markTestIncomplete();
    }

    public function testPatchEntityWithGuardedFields()
    {
        $this->markTestIncomplete();
    }
}
