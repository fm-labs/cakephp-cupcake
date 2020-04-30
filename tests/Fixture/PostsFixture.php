<?php
namespace Cupcake\Test\Fixture;

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
        'body_text' => ['type' => 'text', 'null' => true],
        'is_published' => ['type' => 'boolean', 'default' => false],
        'publish_start' => ['type' => 'datetime', 'default' => null],
        'publish_end' => ['type' => 'datetime', 'default' => null],
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
            'author_id' => null,
            'title' => 'Post 1',
            'slug' => 'post_1',
            'body_text' => '<h1>Post 1</h1>',
            'is_published' => true,
            'publish_start' => null,
            'publish_end' => null,
        ],
        [
            'id' => 2,
            'author_id' => null,
            'title' => 'Post 2',
            'slug' => 'post_2',
            'body_text' => '<h1>Post 2</h1>',
            'is_published' => false,
            'publish_start' => null,
            'publish_end' => null,
        ],
    ];
}
