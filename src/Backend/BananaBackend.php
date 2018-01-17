<?php

namespace Banana\Backend;

use Backend\Event\RouteBuilderEvent;
use Cake\Event\EventListenerInterface;
use Cake\Routing\RouteBuilder;

class BananaBackend implements EventListenerInterface
{
    public function implementedEvents()
    {
        return [
            'Backend.init' => function() {

            },
            'Backend.Router.init' => function(RouteBuilderEvent $event) {
                $event->subject()->scope('/core', ['plugin' => 'Banana', '_namePrefix' => 'core:admin:', 'prefix' => 'admin'], function (RouteBuilder $routes) {

                    $routes->extensions(['json']);

                    $routes->connect('/', ['controller' => 'Dashboard', 'action' => 'index'], ['_name' => 'index']);
                    //$routes->connect('/:controller');
                    $routes->fallbacks('DashedRoute');
                });
            }
        ];
    }
}