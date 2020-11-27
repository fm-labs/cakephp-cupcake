<?php
declare(strict_types=1);

namespace Cupcake\Test\TestCase\Hook;

use Cake\TestSuite\TestCase;
use Cupcake\Hook\Hook;

class HookTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // reset all hooks
        Hook::set(Hook::TYPE_ACTION, []);
        Hook::set(Hook::TYPE_FILTER, []);
    }

    public function testAddAction()
    {
        $trace = [];
        Hook::addAction('test', function () use (&$trace) {
            $trace[] = 'done';
        });

        Hook::doAction('test');
        $this->assertEquals(['done'], $trace);
    }

    public function testAddFilter()
    {
        $this->markTestIncomplete();
    }

    public function testDoFilter()
    {
        $this->markTestIncomplete();
    }

    public function testDoAction()
    {
        $this->markTestIncomplete();
    }
}
