<?php

namespace Banana\Test\TestCase\Model\Table;

use Banana\Model\ArrayTable;

/**
 * Class TestArrayTable
 * @package Banana\Test\TestCase\Model\Table
 */
class TestArrayTable extends ArrayTable
{
    /**
     * @var array
     */
    protected $_items = [
        0 => [
            'title' => 'Test 1',
            'value' => 'Hello',
            'foo' => 'bar',
        ],
        1 => [
            'title' => 'Test 2',
            'value' => 'World',
            'foo' => 'baz',
        ],
        2 => [
            'title' => 'Test 3',
            'value' => 'Bla',
            'foo' => 'oof',
        ],
    ];

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->_items;
    }
}
