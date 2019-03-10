<?php
namespace Banana\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Short description for class.
 */
class PostsFixture extends TestFixture
{

    public $table = 'posts';

    /**
     * fields property
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer'],
        'author_id' => ['type' => 'integer', 'null' => true],
        'title' => ['type' => 'string', 'null' => true],
        'slug' => ['type' => 'string', 'null' => true],
        'is_published' => ['type' => 'boolean', 'default' => false],
        'publish_start' => ['type' => 'datetime', 'default' => null],
        'publish_end' => ['type' => 'datetime', 'default' => null],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
    ];

    /**
     * records property
     *
     * @var array
     */
    public $records = [
    ];
}
