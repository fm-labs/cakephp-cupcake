<?php
namespace Banana\Plugin;

use Banana\Application;
use Cake\Routing\RouteBuilder;

interface PluginInterface
{
    public function bootstrap(Application $app);

    public function routes(RouteBuilder $routes);
}