<?php

namespace Banana;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
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
            'Application.initialize' => 'initialize',
            'Settings.build' => 'buildSettings',
            //'Backend.Menu.build' => ['callable' => 'buildBackendMenu', 'priority' => 80 ],
            //'Backend.Sidebar.build' => ['callable' => 'buildBackendSidebarMenu', 'priority' => 80 ],
            //'Backend.Routes.build' => 'buildBackendRoutes'
        ];
    }

    public function setup(Application $app) {} // provides full access to the application. called externally.

    //public function plugins(PluginManager $plugins) {} // direct access to plugin manager. called externally.

    //public function routes(RouteBuilder $routes) {} // direct access to route builder. called externally.


    public function initialize(Event $event)
    {
        if ($event->subject() instanceof Application) {
            $app = $event->subject();
            $app->plugins()
                ->load('Bootstrap');

            $app->settings()
                ->addGroup('banana', 'Banana')
                ->add('banana', 'Site.title', [])
                ->add('banana', 'Site.title', [])
                ->add('banana', 'Site.title', [])
                ->add('banana', 'Site.title', [])
                ->add('banana', 'Site.title', []);

            $app->cache()
                ->configure('banana', [])
                ->configure('banana_session', []);


            $app->logs()
                ->configure('banana', []);



        }
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

    public function __invoke()
    {
    }
}
