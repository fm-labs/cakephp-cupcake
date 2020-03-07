<?php

namespace Banana\tests\TestCase\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class PublishableBehaviorTest extends TestCase
{
    /**
     * @var Table
     */
    public $Model;

    /**
     * @var array
     */
    public $fixtures = [
        'plugin.Banana.Posts'
    ];

    public function setUp()
    {
        $this->Model = TableRegistry::getTableLocator()->get('Posts');
        $this->Model->addBehavior('Banana.Publishable', []);
    }

    public function tearDown()
    {
        TableRegistry::remove('Model');
        unset($this->Model);
        parent::tearDown();
    }

    public function testFindPublished()
    {
        $entity = $this->Model->newEntity(['title' => 'Publish me', 'is_published' => true]);
        $entity = $this->Model->save($entity);

        $result = $this->Model->find('published')->where(['title' => 'Publish me'])->first();
        $this->assertNotFalse($result);
    }
}
