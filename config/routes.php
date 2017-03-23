<?php
use Cake\Routing\Router;

Router::extensions(['json']);

Router::scope('/core/admin', ['plugin' => 'Banana', '_namePrefix' => 'core:admin:', 'prefix' => 'admin'], function ($routes) {

    $routes->extensions(['json']);

    $routes->connect('/', ['controller' => 'Dashboard', 'action' => 'index'], ['_name' => 'index']);
    //$routes->connect('/:controller');
    $routes->fallbacks('DashedRoute');
});