<?php
use Cake\Routing\Router;

/*
Router::scope('/', function($routes) {
    $routes->connect('/', ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'display', 'home']);

    $routes->fallbacks('DashedRoute');
});
*/

Router::scope('/banana/admin', ['plugin' => 'Banana', '_namePrefix' => 'banana:admin:', 'prefix' => 'admin'], function ($routes) {

    $routes->extensions(['json']);

    $routes->connect('/', ['controller' => 'Dashboard', 'action' => 'index'], ['_name' => 'index']);
    //$routes->connect('/:controller');
    $routes->fallbacks('DashedRoute');
});