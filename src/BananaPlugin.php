<?php

namespace Banana;


use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;

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
            'Settings.get' => 'getSettings',
            'Backend.Menu.get' => ['callable' => 'getBackendMenu', 'priority' => 99 ]
        ];
    }

    public function getSettings(Event $event)
    {
        $event->result['Banana'] = [
            'Site.enabled' => [
                'type' => 'boolean',
                'default' => false
            ],
            'Site.title' => [
                'type' => 'string',
                'default' => 'Untitled Site'
            ],
            'Site.theme' => [
                'type' => 'string',
                'inputType' => 'select',
                'inputModel' => 'Banana.Themes',
                'inputModelFinder' => 'list',
                'default' => null
            ]
        ];
    }

    public function getBackendMenu(Event $event)
    {
        $event->subject()->addItem([
            'title' => 'Settings',
            'url' => ['plugin' => 'Banana', 'controller' => 'Settings', 'action' => 'index'],
            'data-icon' => 'gears',
        ]);
    }
}