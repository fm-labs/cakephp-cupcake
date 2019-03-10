<?php

namespace Banana\Test\TestCase\Model;

use Banana\Test\TestCase\Model\Table\TestInputSchemaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Class InputSchemaTableTest
 *
 * @package Banana\Test\TestCase\Model
 */
class InputSchemaTableTest extends TestCase
{
    /**
     * @var array
     */
    public $fixtures = [
        'plugin.banana.posts'
    ];

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        TableRegistry::config('TestInputSchema', [
           'className' => 'Banana\Test\TestCase\Model\Table\TestInputSchemaTable'
        ]);
    }

    /**
     * @return TestInputSchemaTable
     */
    protected function _table()
    {
        return TableRegistry::get('TestInputSchema');
    }

    /**
     * Test constructor
     */
    public function testConstruct()
    {
        $this->_table();
    }

    /**
     * Test input schema getter
     */
    public function testInputSchema()
    {
        $result = $this->_table()->inputs()->fields();
        $expected = [
            'id' => [
                'type' => 'hidden',
                'label' => 'Id',
                'help' => null,
                'required' => null,
                'searchable' => false,
                'sortable' => false,
            ],
            'title' => [
                'type' => 'text',
                'label' => 'Title',
                'help' => null,
                'required' => null,
                'searchable' => false,
                'sortable' => false,
            ]
        ];

        $this->assertEquals($expected, $result);
    }
}
