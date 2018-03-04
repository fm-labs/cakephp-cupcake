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
            'Plugin.init' => 'initPlugin',
            'Settings.build' => 'buildSettings',
            //'Backend.Menu.build' => ['callable' => 'buildBackendMenu', 'priority' => 80 ],
            'Backend.Sidebar.build' => ['callable' => 'buildBackendSidebarMenu', 'priority' => 80 ],
            'Backend.Routes.build' => 'buildBackendRoutes'
        ];
    }

    public function initPlugin()
    {
        debug("initPlugin");
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
    public function buildBackendSidebarMenu(Event $event)
    {
    }

    /**
     * Backend routes
     */
    public function buildBackendRoutes(RouteBuilderEvent $event)
    {
        $event->subject()->scope('/system', ['plugin' => 'Banana', '_namePrefix' => 'system:admin:', 'prefix' => 'admin'], function ($routes) {
            $routes->fallbacks('DashedRoute');
        });
    }

    public function __invoke()
    {
    }
}
