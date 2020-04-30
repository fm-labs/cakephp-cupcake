<?php
declare(strict_types=1);

namespace Cupcake\Test\TestCase\Model\Behavior;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class PublishBehaviorTest extends TestCase
{
    /**
     * @var \Cake\ORM\Table
     */
    public $Model;

    /**
     * @var array
     */
    public $fixtures = [
        'plugin.Cupcake.Posts',
    ];

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->Model = TableRegistry::getTableLocator()->get('Posts');
        $this->Model->addBehavior('Cupcake.Publish', []);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        TableRegistry::getTableLocator()->remove('Model');
        unset($this->Model);
        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testFindPublished()
    {
        $entity = $this->Model->newEntity(['title' => 'Publish me', 'is_published' => true]);
        $entity = $this->Model->save($entity);

        $result = $this->Model->find('published')->where(['title' => 'Publish me'])->first();
        $this->assertNotFalse($result);
    }
}
