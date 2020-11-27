<?php
declare(strict_types=1);

namespace Cupcake\Menu;

use Cake\Core\App;
use Cake\Core\StaticConfigTrait;

class Menu
{
    use StaticConfigTrait;

    /**
     * @param string $key Menu name.
     * @return \Cupcake\Menu\MenuItemCollection
     */
    public static function get(?string $key): MenuItemCollection
    {
        if ($key === null) {
            return new MenuItemCollection();
        }

        $config = static::getConfig($key);
        if (!$config) {
            throw new \RuntimeException("Menu '${key}' not found");
        }

        return static::resolve($config);
    }

    /**
     * @param array $config The menu config.
     * @return \Cupcake\Menu\MenuItemCollection
     */
    protected static function resolve(array $config): MenuItemCollection
    {
        $class = $config['className'] ?? null;
        if ($class instanceof \Closure) {
            $class = $class();
        }
        if (is_array($class)) {
            return new MenuItemCollection($class);
        } elseif (is_string($class)) {
            $className = App::className($class, 'Menu', 'Menu');
            $class = new $className();
        }

        if ($class instanceof MenuItemCollection) {
            return $class;
        } elseif ($class instanceof MenuProviderInterface) {
            return $class->getCollection();
        }

        throw new \RuntimeException("Invalid menu provider");
    }
}
