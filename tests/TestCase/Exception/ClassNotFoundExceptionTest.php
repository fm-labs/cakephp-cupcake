<?php
declare(strict_types=1);

namespace Cupcake\Test\TestCase\Exception;

use Cupcake\Exception\ClassNotFoundException;
use Cake\TestSuite\TestCase;

class ClassNotFoundExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testException(): void
    {
        $ex = new ClassNotFoundException(['class' => 'MyClass']);
        $this->assertEquals('Class MyClass not found', $ex->getMessage());
        $this->assertEquals(500, $ex->getCode());
    }
}
