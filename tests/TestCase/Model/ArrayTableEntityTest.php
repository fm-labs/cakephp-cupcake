<?php

namespace Banana\Test\TestCase\Model;


use Banana\Model\ArrayTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ArrayTableEntityTest extends TestCase
{
    /**
     * @var ArrayTable
     */
    public $Table;

    public $testData = [
        'title' => 'Test',
        'foo' => 'bar',
        'hello' => 'world',
        'boolean' => true,
        'int' => 1,
    ];

    public function setUp()
    {
        TableRegistry::config('Dummy', [
            'className' => 'Banana\Test\TestCase\Model\Table\DummyArrayTable'
        ]);
        $this->Table = TableRegistry::get('Dummy');
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