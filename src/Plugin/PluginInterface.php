<?php
namespace Banana\Plugin;

use Banana\Application;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;

/**
 * @deprecated since CakePHP 3.7.0
 */
interface PluginInterface
{
    public function bootstrap(Application $app);

    public function routes(RouteBuilder $routes);

    public function middleware(MiddlewareQueue $middleware);
}
