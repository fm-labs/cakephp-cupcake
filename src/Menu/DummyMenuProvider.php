<?php

namespace Cupcake\Menu;

class DummyMenuProvider implements MenuProviderInterface
{
    public function getMenu(string $key): MenuItemCollection
    {
        $menu = new MenuItemCollection();
        $menu->addItem("Dummy1");

        $item = new MenuItem("Dummy2", '#');
        $item->addChild("Child1", '#');
        $item->addChild("Child2");
        $menu->addItem($item);

        return $menu;
    }
}