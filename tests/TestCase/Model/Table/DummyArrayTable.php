<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 3/10/17
 * Time: 8:27 PM
 */

namespace Banana\Test\TestCase\Model\Table;


use Banana\Model\ArrayTable;

class DummyArrayTable extends ArrayTable
{
    protected $_items = [
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

    public function getItems()
    {
        return $this->_items;
    }
}