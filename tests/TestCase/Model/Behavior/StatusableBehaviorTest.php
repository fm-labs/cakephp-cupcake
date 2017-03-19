<?php
namespace Banana\Test\TestCase\Model\Behavior;

use Banana\Model\Behavior\StatusableBehavior;
use Cake\TestSuite\TestCase;

/**
 * Banana\Model\Behavior\StatusableBehavior Test Case
 */
class StatusableBehaviorTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Banana\Model\Behavior\StatusableBehavior
     */
    public $Statusable;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Statusable = new StatusableBehavior();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Statusable);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
