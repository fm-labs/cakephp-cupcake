<?php
declare(strict_types=1);

namespace Banana\Test\TestCase\Model\Behavior;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class SlugBehaviorTest extends TestCase
{
    /**
     * @var \Cake\ORM\Table
     */
    public $Model;

    /**
     * @var array
     */
    public $fixtures = [
        'plugin.Banana.Posts',
    ];

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->Model = TableRegistry::getTableLocator()->get('Posts');
        $this->Model->addBehavior('Banana.Slug', []);
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
    public function testSlugOnSave()
    {
        $entity = $this->Model->newEntity(['title' => 'Slug me']);
        $entity = $this->Model->save($entity);

        $this->assertEquals('slug-me', $entity->get('slug'));
    }
}
