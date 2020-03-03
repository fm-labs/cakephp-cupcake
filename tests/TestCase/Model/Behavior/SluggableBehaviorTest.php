<?php

namespace Banana\tests\TestCase\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class SluggableBehaviorTest extends TestCase
{
    /**
     * @var Table
     */
    public $Model;

    /**
     * @var array
     */
    public $fixtures = [
        'plugin.banana.posts'
    ];

    public function setUp()
    {
        $this->Model = TableRegistry::getTableLocator()->get('Posts');
        $this->Model->addBehavior('Banana.Sluggable', []);
    }

    public function tearDown()
    {
        TableRegistry::remove('Model');
        unset($this->Model);
        parent::tearDown();
    }

    public function testSlugOnSave()
    {
        $entity = $this->Model->newEntity(['title' => 'Slug me']);
        $entity = $this->Model->save($entity);

        $this->assertEquals('slug-me', $entity->slug);
    }
}
