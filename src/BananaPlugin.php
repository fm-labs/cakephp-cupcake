<?php

namespace Banana;

use Backend\Event\RouteBuilderEvent;
use Banana\Backend\BananaBackend;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Routing\Router;
use Content\Lib\ContentManager;
use Settings\SettingsManager;

/**
 * Class BananaPlugin
 *
 * @package Banana
 */
class BananaPlugin implements EventListenerInterface
{

    /**
     * Returns a list of events this object is implementing. When the class is registered
     * in an event manager, each individual method will be associated with the respective event.
     *
     * @see EventListenerInterface::implementedEvents()
     * @return array associative array or event key names pointing to the function
     * that should be called in the object when the respective event is fired
     */
    public function implementedEvents()
    {
        return [
            'Settings.build' => 'buildSettings',
            'Backend.Menu.get' => ['callable' => 'getBackendMenu', 'priority' => 80 ],
            'Backend.Routes.build' => 'buildBackendRoutes'
        ];
    }

    /**
     * @param Event $event
     */
    public function buildSettings(Event $event)
    {
        if ($event->subject() instanceof SettingsManager) {
            $event->subject()->add('Site', 'title', [
                'label' => 'Site title',
                'type' => 'string',
                'default' => 'Untitled Site'
            ]);

        }
    }
    /**
     * @param Event $event
     */
    public function getBackendMenu(Event $event)
    {
        if ($event->subject() instanceof \Banana\Menu\Menu) {
            $event->subject()->addItem([
                'title' => 'Settings',
                'url' => ['plugin' => 'Banana', 'controller' => 'Settings', 'action' => 'manage'],
                'data-icon' => 'sliders',
                'children' => [
                    // @todo add menu children from registered settings sections --
                ]
            ]);
        }
    }

    /**
     * Backend routes
     */
    public function buildBackendRoutes(RouteBuilderEvent $event)
    {
        $event->subject()->scope('/core', ['plugin' => 'Banana', '_namePrefix' => 'core:admin:', 'prefix' => 'admin'], function ($routes) {

            $routes->extensions(['json']);

            $routes->connect('/', ['controller' => 'Dashboard', 'action' => 'index'], ['_name' => 'index']);
            //$routes->connect('/:controller');
            $routes->fallbacks('DashedRoute');
        });
    }

    /**
     * @param Event $event
     */
//    public function getBackendMenu(Event $event)
//    {
//    }

    public function __invoke()
    {
    }
}
