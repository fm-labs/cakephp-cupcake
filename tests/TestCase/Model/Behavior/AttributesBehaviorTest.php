<?php
namespace Banana\Test\TestCase\Model\Behavior;

use Banana\Model\Behavior\AttributesBehavior;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Banana\Model\Behavior\AttributesBehavior Test Case
 */
class AttributesBehaviorTest extends TestCase
{
    public $fixtures = [
        'plugin.banana.attributes',
        'plugin.banana.posts',
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

        $this->table = TableRegistry::get('Banana.Posts');
        $this->table->behaviors()->load('Banana.Attributes');
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
        $this->markTestIncomplete('Not implemented yet.');
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

        $this->assertArraySubset(['attr_string' => 'SomeString1', 'attr_int' => 1], $post->attributes);

        $this->markTestIncomplete('Not implemented yet.');
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
        $posts = $this->table->find()
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
        $posts = $this->table->find()
            ->find('havingAttribute');
    }

    /**
     * Test beforeFind method
     *
     * @return void
     */
    public function testBeforeFind()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildValidator method
     *
     * @return void
     */
    public function testBuildValidator()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test beforeSave method
     *
     * @return void
     */
    public function testBeforeSave()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test afterSave method
     *
     * @return void
     */
    public function testAfterSave()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
