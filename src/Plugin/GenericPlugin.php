<?php
namespace Banana\Plugin;

use Banana\Application;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;

class GenericPlugin implements PluginInterface
{

    /**
     * {@inheritDoc}
     */
    public function bootstrap(Application $app)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function routes(RouteBuilder $routes)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function middleware(MiddlewareQueue $middleware)
    {
    }
}
