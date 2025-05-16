<?php
declare(strict_types=1);

namespace Cupcake\Model;

use Cake\Core\App;
use Cake\Utility\Inflector;
use RuntimeException;

class EntityTypeRegistry
{
    protected static $types = [];

    /**
     * @param string $ns Type namespace
     * @return void
     */
    public static function registerNs(string $ns): void
    {
        if (!isset(self::$types[$ns])) {
            self::$types[$ns] = [];
        }
    }

    /**
     * @param string $ns Type namespace
     * @param string $alias Type alias
     * @param array $config Type config
     * @return void
     */
    public static function register(string $ns, string $alias, array $config = []): void
    {
        static::registerNs($ns);

        if (isset(self::$types[$ns][$alias])) {
            throw new RuntimeException(sprintf("Can not register type '%s':'%s': Already registered", $ns, $alias));
        }

        if (!isset($config['className'])) {
            throw new RuntimeException(sprintf("Can not register type '%s':'%s': No className defined", $ns, $alias));
        }

        if (!isset($config['title'])) {
            $config['title'] = Inflector::humanize($alias);
        }

        self::$types[$ns][$alias] = $config;
    }

    /**
     * @param string $ns Type namespace
     * @param array $configs Type configs
     * @return void
     */
    public static function registerMultiple(string $ns, array $configs): void
    {
        foreach ($configs as $alias => $config) {
            static::register($ns, $alias, $config);
        }
    }

    /**
     * Get list of aliases registered for a namespace.
     *
     * @param string $ns Type namespace.
     * @return array
     */
    public static function registered(string $ns): array
    {
        return static::$types[$ns] ?? [];
    }

    /**
     * @param string $ns Type namespace
     * @param string $alias Type alias
     * @param callable|null $factory Custom factory
     * @return object
     */
    public static function createInstance(string $ns, string $alias, ?callable $factory = null): EntityTypeInterface
    {
        if (!isset(self::$types[$ns][$alias])) {
            throw new RuntimeException(sprintf("Can not create type '%s':'%s': Not registered", $ns, $alias));
        }

        [, $nsClass] = pluginSplit($ns);

        $config = self::$types[$ns][$alias];
        $className = $config['className'];
        // for example:
        // - EntityClass: \Content\Model\Entity\Article
        // -> Plugin: Content
        // -> Entity: Article
        // -> Namespace: Content.Article
        // -> EntityTypeClass: \Content\Model\Entity\Article\{Type}Type
        // -> EntityTypeInterface: \Content\Model\Entity\Article\TypeInterface
        $className = App::className($className, 'Model/Entity/' . $nsClass, 'Type');
        unset($config['className']);

        if (!$className) {
            throw new RuntimeException(sprintf("Can not create type '%s':'%s': Invalid className", $ns, $alias));
        }
        if (!class_exists($className)) {
            throw new RuntimeException(sprintf("Can not create type '%s':'%s': Class not found", $ns, $alias));
        }

        if ($factory === null) {
            $factory = function ($className) use ($config) {
                return new $className($config);
            };
        }

        return $factory($className);
    }
}
