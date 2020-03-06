<?php

namespace Banana\Menu;

use Cake\Event\Event;
use Cake\Event\EventManager;

class MenuBuilder
{
    public static function build($id, $eventName = 'Menu.build')
    {
        $menu = new Menu();
        $event = EventManager::instance()->dispatch(new Event($eventName, null, ['menu' => $menu, 'menuId' => $id]));

        return $event->getData('menu');
    }
}
