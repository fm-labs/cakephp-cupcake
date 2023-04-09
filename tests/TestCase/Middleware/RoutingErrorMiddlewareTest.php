<?php
declare(strict_types=1);

namespace Cupcake\Test\TestCase\Middleware;

use Cake\TestSuite\TestCase;
use Cupcake\Middleware\RoutingErrorMiddleware;

/**
 * Cupcake\Middleware\RoutingErrorMiddleware Test Case
 */
class RoutingErrorMiddlewareTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Cupcake\Middleware\RoutingErrorMiddleware
     */
    protected $RoutingError;

    /**
     * Test process method
     *
     * @return void
     * @uses \Cupcake\Middleware\RoutingErrorMiddleware::process()
     */
    public function testProcess(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
