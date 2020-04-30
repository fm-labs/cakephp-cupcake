<?php
declare(strict_types=1);

namespace Cupcake\Test\TestCase\Exception;

use Cupcake\Exception\NotSupportedException;
use Cake\TestSuite\TestCase;

class NotSupportedExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testException(): void
    {
        $ex = new NotSupportedException(['feature' => 'Test Provider']);
        $this->assertEquals('Sry, Test Provider is currently not supported', $ex->getMessage());
        $this->assertEquals(500, $ex->getCode());
    }
}
