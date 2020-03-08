<?php
namespace Banana\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Fixture for testing json, serialized and base64 database data types
 */
class DatatypesFixture extends TestFixture
{

    /**
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'biginteger'],
        'json' => ['type' => 'text', 'null' => true],
        'serialized' => ['type' => 'text', 'null' => true],
        'base64' => ['type' => 'text', 'null' => true],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    /**
     * @var array
     */
    public $records = [];
}
