<?php
declare(strict_types=1);

namespace Cupcake\Menu;

use Cake\Core\App;
use Cake\Core\StaticConfigTrait;

class MenuManager
{
    use StaticConfigTrait;

    /**
     * @param string|null $key Menu name.
     * @return \Cupcake\Menu\MenuItemCollection
     */
    public static function get(?string $key): MenuItemCollection
    {
        if ($key === null) {
            return new MenuItemCollection();
        }

        $config = static::getConfig($key);
        if (!$config) {
            throw new \RuntimeException("Menu '{$key}' not found");
        }

        return static::resolve($key, $config);
    }

    /**
     * @param array $config The menu config.
     * @return \Cupcake\Menu\MenuItemCollection
     */
    protected static function resolve(string $key, array $config): MenuItemCollection
    {
        $class = $config['className'] ?? null;
        if (!$class) {
            throw new \RuntimeException('No menu provider class defined for menu ' . $key);
        }
        unset($config['className']);

        if ($class instanceof \Closure) {
            $class = $class($config);
        }
        if (is_array($class)) {
            return new MenuItemCollection($class);
        }
        if (is_string($class)) {
            $className = App::className($class, 'Menu', 'Menu');
            $class = new $className($config);
        }
        if ($class instanceof MenuProviderInterface) {
            $class = $class->getMenu($key);
        }
        if (!($class instanceof MenuItemCollection)) {
            throw new \RuntimeException('Invalid menu provider');
        }

        return $class;
    }
}
