<?php
namespace Cupcake\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Short description for class.
 */
class AttributesFixture extends TestFixture
{
    public $table = 'attributes';

    /**
     * fields property
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer'],
        'model' => ['type' => 'string', 'null' => false, 'length' => 255],
        'foreign_key' => ['type' => 'integer', 'null' => false, 'length' => 10, 'unsigned' => true],
        'key' => ['type' => 'string', 'null' => false, 'length' => 255],
        'value' => ['type' => 'text', 'null' => true],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    /**
     * records property
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'model' => 'Cupcake.Posts',
            'foreign_key' => 1,
            'key' => 'attr_string',
            'value' => 'SomeString1',
        ],
        [
            'id' => 2,
            'model' => 'Cupcake.Posts',
            'foreign_key' => 1,
            'key' => 'attr_int',
            'value' => 1,
        ],
        [
            'id' => 3,
            'model' => 'Cupcake.Posts',
            'foreign_key' => 2,
            'key' => 'attr_string',
            'value' => 'SomeString2',
        ],
        [
            'id' => 4,
            'model' => 'Cupcake.Posts',
            'foreign_key' => 2,
            'key' => 'attr_text',
            'value' => 'Hello World',
        ],
    ];
}
