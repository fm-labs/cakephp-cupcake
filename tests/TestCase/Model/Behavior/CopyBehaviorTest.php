<?php
declare(strict_types=1);

namespace Banana\Test\TestCase\Model\Behavior;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class CopyBehaviorTest extends TestCase
{
    /**
     * @var \Cake\ORM\Table
     */
    public $posts;

    /**
     * @var \Cake\Datasource\ConnectionInterface
     */
    public $connection;

    /**
     * @var array
     */
    public $fixtures = [
        'plugin.Banana.Posts',
    ];

    public $copyConfig = [
        'fields' => ['author_id', 'title', 'body_text'],
    ];

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->connection = ConnectionManager::get('test');

        $this->posts = $this->getTableLocator()->get('Posts', [
            'table' => 'posts',
            'connection' => $this->connection,
        ]);
        $this->posts->addBehavior('Banana.Copy', $this->copyConfig);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->getTableLocator()->remove('Posts');
        unset($this->posts);
    }

    /**
     * @return void
     */
    public function testConfig(): void
    {
        $config = $this->posts->getBehavior('Copy')->getConfig();
        $this->assertArraySubset($this->copyConfig, $config);
    }

    /**
     * @return void
     */
    public function testCopyEntity(): void
    {
        $post = $this->posts->get(1);

        /** @var \Cake\Datasource\EntityInterface $copy */
        $copy = $this->posts->copyEntity($post);
        $this->assertEquals($post->get('author_id'), $copy->get('author_id'));
        $this->assertEquals($post->get('title'), $copy->get('title'));
        $this->assertEquals($post->get('body_text'), $copy->get('body_text'));
        $this->assertNotEquals($post->get('id'), $copy->get('id'));
        $this->assertNotEquals($post->get('id'), $copy->get('slug'));
        $this->assertNotEquals($post->get('id'), $copy->get('is_published'));
        $this->assertTrue($copy->isNew());
    }

    /**
     * @return void
     */
    public function testCopy(): void
    {
        $post = $this->posts->get(1);

        /** @var \Cake\Datasource\EntityInterface $copy */
        $copy = $this->posts->copy($post);
        $this->assertFalse($copy->isNew());
        $this->assertNotNull($copy->get('id'));
    }
}
