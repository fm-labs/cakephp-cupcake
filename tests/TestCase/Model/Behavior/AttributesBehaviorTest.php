<?php
declare(strict_types=1);

namespace Cupcake\Test\TestCase\Model\Behavior;

use Cake\ORM\Association\HasMany;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cupcake\Model\AttributesSchema;

/**
 * Cupcake\Model\Behavior\AttributesBehavior Test Case
 */
class AttributesBehaviorTest extends TestCase
{
    public $fixtures = [
        'plugin.Cupcake.Attributes',
        'plugin.Cupcake.Posts',
    ];

    /**
     * @var \Cake\ORM\Table
     * @method \Cake\ORM\Table createAttribute()
     */
    public $table;

    /**
     * setUp method
     *
     * @return void
     * @throws \Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->table = TableRegistry::getTableLocator()->get('Cupcake.Posts');
        $this->table->behaviors()->load('Cupcake.Attributes', [
            'propertyName' => 'attributes_data',
            'schema' => [
                'test_string' => ['default' => null],
                'test_required' => ['required' => true, 'type' => 'int'],
                'test_attribute' => [],
                'my_attribute' => [],
            ],
        ]);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->table);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize(): void
    {
        $this->assertTrue($this->table->hasAssociation('PostsAttributes'));
    }

    /**
     * @return void
     */
    public function testGetAttributesTable(): void
    {
        $this->assertInstanceOf(HasMany::class, $this->table->getAttributesTable());
    }

    /**
     * @return void
     */
    public function testGetAttributesSchema(): void
    {
        $schema = $this->table->getAttributesSchema();
        $this->assertInstanceOf(AttributesSchema::class, $schema);

        $this->assertArrayHasKey('test_string', $schema);
        $this->assertArrayHasKey('test_required', $schema);
        $this->assertArrayHasKey('test_attribute', $schema);
        $this->assertArrayHasKey('my_attribute', $schema);
        $this->assertEquals(['type' => 'string', 'default' => null, 'required' => null, 'input' => []], $schema['test_string']);
        $this->assertEquals(['type' => 'int', 'default' => null, 'required' => true, 'input' => []], $schema['test_required']);
        $this->assertEquals(['type' => 'string', 'default' => null, 'required' => null, 'input' => []], $schema['test_attribute']);
        $this->assertEquals(['type' => 'string', 'default' => null, 'required' => null, 'input' => []], $schema['my_attribute']);
    }

    public function testCreateAttribute()
    {
        $entity = $this->table->get(1);
        $attr = $this->table->createAttribute($entity, 'test_string', 'Hello');
        $this->assertNotFalse($this->table->saveAttribute($attr));
    }

    /**
     * @return void
     */
    public function testValidation(): void
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
        $this->assertTrue($post->getError('test_required')['_required'] ? true : false);
    }

    /**
     * Test findWithAttributes method
     *
     * @return void
     */
    public function testFindWithAttributes(): void
    {
        $post = $this->table->find()
            ->find('withAttributes')
            ->first();
        $this->assertArrayHasKey('attr_string', $post->toArray());
        $this->assertArrayHasKey('attr_int', $post->toArray());
        $this->assertEquals('SomeString1', $post->toArray()['attr_string']);
        $this->assertEquals(1, $post->toArray()['attr_int']);
    }

    /**
     * @return void
     */
    public function testFindByAttribute(): void
    {
        $posts = $this->table
            ->find('byAttribute', ['attr_string' => 'SomeString1']);
        $this->assertEquals(1, $posts->count());

        $posts = $this->table
            ->find('byAttribute', ['non_existent' => null]);
        $this->assertEquals(0, $posts->count());

        $this->expectException('\InvalidArgumentException');
        $this->table->find()
            ->find('byAttribute');
    }

    /**
     * @return void
     */
    public function testFindHavingAttribute(): void
    {
        $posts = $this->table->find('havingAttribute', ['attr_string']);
        $this->assertEquals(2, $posts->count());

        $posts = $this->table->find('havingAttribute', ['attr_text']);
        $this->assertEquals(1, $posts->count());

        //$posts = $this->table->find('havingAttribute', ['attr_string', 'attr_text']);
        //$this->assertEquals(1, $posts->count());

        $posts = $this->table->find('havingAttribute', ['non_existent']);
        $this->assertEquals(0, $posts->count());

        $this->expectException('\InvalidArgumentException');
        $this->table->find('havingAttribute');
    }

    /**
     * @return void
     */
    public function testCrudWithAttributesData(): void
    {
        $attributes = [
            'test_string' => 'bar',
            'test_required' => 1,
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
            'attributes_data' => $attributes,
        ]);

        $post = $this->table->save($post);
        $this->assertNotFalse($post);

        // read
        $post = $this->table->find()
            ->find('withAttributes')
            ->where(['Posts.id' => $post->id])
            ->first();
        $this->assertArrayHasKey('test_string', $post->get('attributes_data'));
        $this->assertArrayHasKey('test_required', $post->get('attributes_data'));
        $this->assertEquals('bar', $post->get('attributes_data')['test_string']);
        $this->assertEquals(1, $post->get('attributes_data')['test_required']);

        // update
        $attributes2 = [
            'test_string' => 'baz',
            'test_required' => 2,
            'test_attribute' => 'test',
        ];
        //$post->set('attributes_data', $attributes2);
        $post = $this->table->patchEntity($post, ['attributes_data' => $attributes2]);
        $post = $this->table->save($post);

        // read again
        $post = $this->table->find()
            ->find('withAttributes')
            ->where(['Posts.id' => $post->id])
            ->first();
        $this->assertArrayHasKey('test_string', $post->get('attributes_data'));
        $this->assertArrayHasKey('test_required', $post->get('attributes_data'));
        $this->assertArrayHasKey('test_attribute', $post->get('attributes_data'));
        $this->assertEquals('baz', $post->get('attributes_data')['test_string']);
        $this->assertEquals(2, $post->get('attributes_data')['test_required']);
        $this->assertEquals('test', $post->get('attributes_data')['test_attribute']);

        // delete
        $this->table->delete($post);

        $orphanedAttributes = TableRegistry::getTableLocator()->get('Cupcake.Attributes')
            ->find()
            ->where([
                'model' => $this->table->getRegistryAlias(),
                'foreign_key' => $post->id,
            ])
            ->count();
        $this->assertEquals(0, $orphanedAttributes);
    }

    /**
     * @return void
     */
    public function testCrudWithAttributeDirectAccessor(): void
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
            'my_attribute' => 'foo',
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

        $orphanedAttributes = TableRegistry::getTableLocator()->get('Cupcake.Attributes')
            ->find()
            ->where([
                'model' => $this->table->getRegistryAlias(),
                'foreign_key' => $post->id,
            ])
            ->count();
        $this->assertEquals(0, $orphanedAttributes);
    }
}
