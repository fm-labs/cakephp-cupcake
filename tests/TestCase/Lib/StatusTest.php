<?php

namespace Banana\tests\TestCase\Lib;

use Banana\Lib\Status;
use PHPUnit\Framework\TestCase;

/**
 * Class StatusTest
 *
 * @package Banana\tests\TestCase\Lib
 */
class StatusTest extends TestCase
{
    /**
     * Test constructor with default params
     */
    public function testConstructWithDefaults()
    {
        $status = new Status(0);
        $this->assertEquals(0, $status->getStatus());
        $this->assertEquals(0, $status->getLabel());
        $this->assertEquals('default', $status->getClass());
    }

    /**
     * Test constructor with custom params
     */
    public function testConstruct()
    {
        $status = new Status(0, 'Test Label', 'test-class');
        $this->assertEquals(0, $status->getStatus());
        $this->assertEquals('Test Label', $status->getLabel());
        $this->assertEquals('test-class', $status->getClass());
    }

    /**
     * Test magic __toString method
     */
    public function testToString()
    {
        $status = new Status(0, 'Test Label', 'test-class');
        $this->assertEquals("0", (string) $status);
    }

    /**
     * Test toHtml method
     */
    public function testToHtml()
    {
        $status = new Status(0, 'Test Label', 'test-class');
        $this->assertEquals('<span class="label label-test-class">Test Label</span>', $status->toHtml());
    }
}
