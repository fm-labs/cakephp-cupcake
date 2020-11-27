<?php
declare(strict_types=1);

namespace Cupcake;

use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;

/**
 * Class Cupcake
 *
 * @package Cupcake
 * @todo Refactor as (service) container
 * @todo Caching service
 * @todo Log service
 */
class Cupcake
{
    /**
     * @deprecated
     */
    public const VERSION = "0.4.0";

    /**
     * @var string Default mailer class
     */
    public static $mailerClass = 'Cake\Mailer\Mailer';

    /**
     * @var array List of Cupcake instances. Singleton holder.
     */
    protected static $_instances = [];

    /**
     * @var array Map of registered filters
     */
    protected static $_filters = [];

    /**
     * Cupcake-app wide common mailer instance
     *
     * @return \Cake\Mailer\Mailer
     */
    public static function getMailer()
    {
        return new self::$mailerClass();
    }

    /**
     * Singleton getter
     * @return \Cupcake\Cupcake
     * @throws \Exception
     */
    public static function init(PluginApplicationInterface $app)
    {
        if (isset(self::$_instances[0])) {
            throw new \Exception('Cupcake::init: Already initialized');
        }
        self::$_instances[0] = new self($app);
    }

    /**
     * Singleton getter
     * @return \Cupcake\Cupcake
     * @throws \Exception
     */
    public static function getInstance(): self
    {
        if (!isset(self::$_instances[0])) {
            throw new \Exception('Cupcake::getInstance: Not initialized');
        }

        return self::$_instances[0];
    }

    /**
     * Static access to the plugin handler
     */
    public static function plugin(string $pluginName): \Cake\Core\PluginInterface
    {
        return self::getInstance()->app()->getPlugins()->get($pluginName);
    }

    /**
     * Static access to the plugin info
     */
    public static function pluginInfo(string $pluginName): array
    {
        return self::getInstance()->app()->getPluginInfo($pluginName);
    }

    /**
     * Get Cupcake Cake version
     *
     * @return string
     */
    public static function version(): string
    {
        //deprecationWarning("Cupcake::version() is deprecated");
        return self::VERSION;
    }

    /**
     * Singleton instance constructor
     * @param \Cupcake\Application $app
     */
    public function __construct(PluginApplicationInterface $app)
    {
        $this->_app = $app;
    }

    /**
     * @return \Cake\Core\PluginCollection
     */
    public function plugins()
    {
        return $this->_app->getPlugins();
    }

    /**
     * @return \Cupcake\Application
     */
    public function app(): \Cupcake\Application
    {
        return $this->_app;
    }

    /**
     * Add a filter.
     *
     * @param string $name
     * @param callable $callback
     */
    public static function addFilter(string $name, callable $callback): void
    {
        self::$_filters['filter:'.$name][] = $callback;
    }

    /**
     * Add an action filter.
     *
     * @param string $name
     * @param callable $callback
     */
    public static function addAction(string $name, callable $callback): void
    {
        self::$_filters['action:'.$name][] = $callback;
    }

    /**
     * Apply a filter.
     *
     * @param string $name
     * @param array $data
     * @param array $options
     * @return array|mixed|null
     */
    public static function doFilter(string $name, array $data, array $options = [])
    {
        // apply local filters
        if (isset(self::$_filters['filter:'.$name])) {
            foreach(self::$_filters['filter:'.$name] as $filter) {
                $data = call_user_func($filter, $data, $options);
            }
        }

        // dispatch filter event
        $event = EventManager::instance()->dispatch(new Event('Filter.' . $name, null, $data));
        $data = $event->getData();

        return $data;
    }

    /**
     * Apply an action filter.
     *
     * @param string $name
     * @param array $data
     */
    public static function doAction(string $name, array $data = []): void
    {
        // apply local action filters
        if (isset(self::$_filters['action:'.$name])) {
            foreach(self::$_filters['action:'.$name] as $filter) {
                call_user_func($filter, $data);
            }
        }

        // dispatch action filter event
        EventManager::instance()->dispatch(new Event('Action.' . $name, null, $data));
    }
}
