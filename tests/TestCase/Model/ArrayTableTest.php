<?php

namespace Banana\Test\TestCase\Model;

use Banana\Model\ArrayTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ArrayTableTest extends TestCase
{
    public function setUp()
    {
        TableRegistry::getTableLocator()->setConfig('TestArray', [
           'className' => 'Banana\Test\TestCase\Model\Table\TestArrayTable'
        ]);
    }

    /**
     * @return ArrayTable
     */
    protected function _table()
    {
        return TableRegistry::getTableLocator()->get('TestArray');
    }

    public function testConstruct()
    {
        $this->_table();
    }

    public function testFindAll()
    {
        $Table = $this->_table();

        $expected = [
            0 => [
                'title' => 'Test 1',
                'value' => 'Hello',
                'foo' => 'bar'
            ],
            1 => [
                'title' => 'Test 2',
                'value' => 'World',
                'foo' => 'baz'
            ],
            2 => [
                'title' => 'Test 3',
                'value' => 'Bla',
                'foo' => 'oof'
            ]
        ];
        $this->assertEquals($expected, $Table->find()->all()->toArray());
    }

    public function testFindConditional()
    {
        $Table = $this->_table();

        $expected = [
            0 => [
                'title' => 'Test 1',
                'value' => 'Hello',
                'foo' => 'bar'
            ],
        ];
        $this->assertEquals($expected, $Table->find()->where(['foo' => 'bar'])->all()->toArray());
    }

    public function testFindConditionalCallback()
    {
        $Table = $this->_table();

        $expected = [
            0 => [
                'title' => 'Test 1',
                'value' => 'Hello',
                'foo' => 'bar'
            ],
        ];

        $result = $Table->find()
            ->where(function ($result) {
                return ($result['foo'] == 'bar') ? true : false;
            })
            ->all()
            ->toArray();
        $this->assertEquals($expected, $result);
    }

    public function testFindList()
    {
        $Table = $this->_table();

        $expected = [
            0 => 'Test 1',
            1 => 'Test 2',
            2 => 'Test 3'
        ];
        $this->assertEquals($expected, $Table->find('list')->all()->toArray());
    }

    public function testFindListCustomValueField()
    {
        $Table = $this->_table();

        $expected = [
            0 => 'bar',
            1 => 'baz',
            2 => 'oof'
        ];
        $this->assertEquals($expected, $Table->find('list', ['valueField' => 'foo'])->all()->toArray());
    }

    public function testFindConditionalList()
    {
        $Table = $this->_table();

        $expected = [
            0 => 'Test 1'
        ];
        $this->assertEquals($expected, $Table->find('list')->where(['foo' => 'bar'])->all()->toArray());
    }

    public function testNewEntity()
    {
        $Table = $this->_table();
        $entity = $Table->newEntity();

        $this->assertInstanceOf('\ArrayObject', $entity);
        $this->assertInstanceOf('\ArrayAccess', $entity);
        $this->assertInstanceOf('\Cake\Datasource\EntityInterface', $entity);
        $this->assertInstanceOf('\Banana\Model\ArrayTableEntity', $entity);
    }

    public function testGet()
    {
        $Table = $this->_table();
        $expected = [
            'title' => 'Test 1',
            'value' => 'Hello',
            'foo' => 'bar'
        ];
        $this->assertEquals($expected, $Table->get(0)->toArray());
    }
}
