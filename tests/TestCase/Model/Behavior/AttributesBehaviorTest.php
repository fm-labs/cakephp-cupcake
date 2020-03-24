<?php
namespace Banana\Test\TestCase\Model\Behavior;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Banana\Model\Behavior\AttributesBehavior Test Case
 */
class AttributesBehaviorTest extends TestCase
{
    public $fixtures = [
        'plugin.Banana.Attributes',
        'plugin.Banana.Posts',
    ];

    /**
     * @var \Cake\ORM\Table
     */
    public $table;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->table = TableRegistry::getTableLocator()->get('Banana.Posts');
        $this->table->behaviors()->load('Banana.Attributes', [
            'attributesPropertyName' => 'attributes_data',
            'attributes' => [
                'test_string' => ['default' => null],
                'test_required' => ['required' => true],
                'test_attribute' => [],
                'my_attribute' => []
            ]
        ]);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->table);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->assertTrue($this->table->hasAssociation('Attributes'));
    }

    public function testGetAttributesTable()
    {
        $this->markTestIncomplete();
        //$this->assertInstanceOf(\Banana\Model\Table\AttributesTable::class, $this->table->getAttributesTable());
    }

    public function testGetAttributesSchema()
    {
        $schema = $this->table->getAttributesSchema();

        $expected = [
            'test_string' => ['default' => null],
            'test_required' => ['required' => true],
            'test_attribute' => [],
            'my_attribute' => []
        ];
        $this->assertArraySubset($expected, $schema);
    }


    public function testValidation()
    {
        // create
        $post = $this->table->newEntity([
            'author_id' => null,
            'title' => 'Post New',
            'slug' => 'post_new',
            'body_text' => '<h1>Post New</h1>',
            'is_published' => true,
            'publish_start' => null,
            'publish_end' => null,
        ]);

        //$post = $this->table->patchEntity($post, ['my_attribute' => 'foo']);
        $this->assertTrue(($post->getError('test_required')['_required']) ? true : false);
    }

    /**
     * Test findWithAttributes method
     *
     * @return void
     */
    public function testFindWithAttributes()
    {
        $post = $this->table->find()
            ->find('withAttributes')
            ->first();

        $this->assertArraySubset(['attr_string' => 'SomeString1', 'attr_int' => 1], $post->toArray());
    }

    public function testFindByAttribute()
    {
        $posts = $this->table->find()
            ->find('byAttribute', ['attr_string' => 'SomeString1']);
        $this->assertEquals(1, $posts->count());

        $posts = $this->table->find()
            ->find('byAttribute', ['non_existent' => null]);
        $this->assertEquals(0, $posts->count());

        $this->expectException('\InvalidArgumentException');
        $this->table->find()
            ->find('byAttribute');
    }

    public function testFindHavingAttribute()
    {
        $posts = $this->table->find()
            ->find('havingAttribute', ['attr_string']);
        $this->assertEquals(2, $posts->count());

        $posts = $this->table->find()
            ->find('havingAttribute', ['attr_text']);
        $this->assertEquals(1, $posts->count());

        $posts = $this->table->find()
            ->find('havingAttribute', ['non_existent' => null]);
        $this->assertEquals(0, $posts->count());

        $this->expectException('\InvalidArgumentException');
        $this->table->find()
            ->find('havingAttribute');
    }

    public function testCrudWithAttributesData()
    {
        $this->markTestIncomplete();

        $attributes = [
            'test_string' => 'bar',
            'test_required' => 1
        ];

        // create
        $post = $this->table->newEntity([
            'author_id' => null,
            'title' => 'Post New',
            'slug' => 'post_new',
            'body_text' => '<h1>Post New</h1>',
            'is_published' => true,
            'publish_start' => null,
            'publish_end' => null,
            'attributes_data' => $attributes
        ]);

        $post = $this->table->save($post);
        $this->assertNotFalse($post);

        // read
        $post = $this->table->find()
            ->find('withAttributes')
            ->where(['Posts.id' => $post->id])
            ->first();

        $this->assertArraySubset($attributes, $post->get('attributes_data'));

        // update
        $attributes2 = [
            'test_string' => 'baz',
            'test_required' => 2,
            'test_attribute' => 'test'
        ];
        $post->set('attributes_data', $attributes2);
        $post = $this->table->save($post);

        // read again
        $post = $this->table->find()
            ->find('withAttributes')
            ->where(['Posts.id' => $post->id])
            ->first();

        $this->assertArraySubset($attributes2, $post->get('attributes_data'));

        // delete
        $this->table->delete($post);

        $orphanedAttributes = TableRegistry::getTableLocator()->get('Banana.Attributes')
            ->find()
            ->where([
                'model' => $this->table->getRegistryAlias(),
                'foreign_key' => $post->id
            ])
            ->count();
        $this->assertEquals(0, $orphanedAttributes);
    }

    public function testCrudWithAttributeDirectAccessor()
    {
        // create
        $post = $this->table->newEntity([
            'author_id' => null,
            'title' => 'Post New',
            'slug' => 'post_new',
            'body_text' => '<h1>Post New</h1>',
            'is_published' => true,
            'publish_start' => null,
            'publish_end' => null,
            'test_required' => 0,
            'my_attribute' => 'foo'
        ]);

        $post = $this->table->save($post);
        $this->assertNotFalse($post);

        // read
        $post = $this->table->find()
            ->find('withAttributes')
            ->where(['Posts.id' => $post->id])
            ->first();

        $this->assertEquals('foo', $post->get('my_attribute'));

        // update
        $post->set('my_attribute', 'bar');
        $post = $this->table->save($post);

        // read again
        $post = $this->table->find()
            ->find('withAttributes')
            ->where(['Posts.id' => $post->id])
            ->first();

        $this->assertEquals('bar', $post->get('my_attribute'));

        // delete
        $this->table->delete($post);

        $orphanedAttributes = TableRegistry::getTableLocator()->get('Banana.Attributes')
            ->find()
            ->where([
                'model' => $this->table->getRegistryAlias(),
                'foreign_key' => $post->id
            ])
            ->count();
        $this->assertEquals(0, $orphanedAttributes);
    }
}
