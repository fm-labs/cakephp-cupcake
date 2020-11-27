<?php
declare(strict_types=1);

namespace Cupcake\Menu;

interface MenuProviderInterface
{
    /**
     * @return \Cupcake\Menu\MenuItemCollection
     */
    public function getCollection(): MenuItemCollection;
}
