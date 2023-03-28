<?php
declare(strict_types=1);

namespace Cupcake;

use Cake\Cache\Cache;
use Cake\Cache\Engine\FileEngine;
use Cake\Core\Plugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Core\PluginCollection;
use Cake\Core\PluginInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Log\Engine\FileLog;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use DebugKit\Cache\Engine\DebugEngine;
use Exception;

/**
 * Class Cupcake
 *
 * @package Cupcake
 */
class Cupcake
{
    /**
     * @var string Default mailer class
     */
    public static string $mailerClass = 'Cake\Mailer\Mailer';

    /**
     * @var array List of Cupcake instances. Singleton holder.
     */
    protected static array $_instances = [];

    /**
     * @var array Map of registered filters
     */
    protected static array $_filters = [];

    /**
     * @var \Cake\Core\PluginApplicationInterface|\Cupcake\Application Application instance
     */
    private static PluginApplicationInterface|Application $_app;

    /**
     * Cupcake-app wide common mailer instance
     *
     * @return \Cake\Mailer\Mailer
     */
    public static function getMailer(): Mailer
    {
        return new self::$mailerClass();
    }

    /**
     * Initialize with application instance
     *
     * @param \Cake\Core\PluginApplicationInterface $app The application instance
     * @return void
     * @throws \Exception
     * @deprecated Use setApplication/getInstance instead
     */
    public static function init(PluginApplicationInterface $app): void
    {
        //if (isset(self::$_instances[0])) {
        //    throw new \Exception('Cupcake::init: Already initialized');
        //}
        //self::$_instances[0] = new self($app);
        deprecationWarning('Cupcake::init() is deprecated. Use getInstance() instead.');
        self::setApplication($app);
        self::getInstance();
    }

    /**
     * @param \Cake\Core\PluginApplicationInterface $app
     * @return void
     */
    public static function setApplication(PluginApplicationInterface $app): void
    {
        static::$_app = $app;
    }

    /**
     * Singleton getter
     *
     * @return self
     * @throws \Exception
     */
    public static function getInstance(): self
    {
        if (!isset(self::$_instances[0])) {
            if (!static::$_app) {
                throw new Exception('Cupcake: No application set.');
            }
            self::$_instances[0] = new self();
        }

        return self::$_instances[0];
    }

    /**
     * Static access to the plugin handler
     *
     * @return \Cake\Core\PluginInterface
     */
    public static function plugin(string $pluginName): PluginInterface
    {
        return self::getInstance()->app()->getPlugins()->get($pluginName);
    }

    /**
     * Static access to the plugin info.
     *
     * @param string $pluginName The plugin name
     * @return array
     * @deprecated Use PluginManager::getPluginInfo() instead.
     */
    public static function pluginInfo(string $pluginName): array
    {
        deprecationWarning('Cupcake::pluginInfo is deprecated. Use PluginManager::getPluginInfo() instead');

        return PluginManager::getPluginInfo($pluginName);
    }

    /**
     * Singleton instance constructor.
     *
     * @param \Cupcake\Application $app The application instance
     */
    protected function __construct()
    {
    }

    /**
     * @return \Cake\Core\PluginCollection
     */
    public function plugins(): PluginCollection
    {
        return $this->app()->getPlugins();
    }

    /**
     * @return \Cupcake\Application
     */
    public function app(): Application
    {
        return static::$_app;
    }

    /**
     * Add a filter.
     *
     * @param string $name Filter name
     * @param callable $callback Filter callback
     * @return void
     * @todo Use Hook class instead
     */
    public static function addFilter(string $name, callable $callback): void
    {
        self::$_filters['filter:' . $name][] = $callback;
    }

    /**
     * Add an action filter.
     *
     * @param string $name Filter name
     * @param callable $callback Filter callback
     * @return void
     * @todo Use Hook class instead
     */
    public static function addAction(string $name, callable $callback): void
    {
        self::$_filters['action:' . $name][] = $callback;
    }

    /**
     * Apply a filter.
     *
     * @param string $name Filter name
     * @param array $data Filter data
     * @param array $options Filter options
     * @return mixed|array|null Filter result
     * @todo Use Hook class instead
     */
    public static function doFilter(string $name, array $data, array $options = []): mixed
    {
        // apply local filters
        if (isset(self::$_filters['filter:' . $name])) {
            foreach (self::$_filters['filter:' . $name] as $filter) {
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
     * @param string $name Filter name
     * @param array $data Filter callback
     * @return void
     * @todo Use Hook class instead
     */
    public static function doAction(string $name, array $data = []): void
    {
        // apply local action filters
        if (isset(self::$_filters['action:' . $name])) {
            foreach (self::$_filters['action:' . $name] as $filter) {
                call_user_func($filter, $data);
            }
        }

        // dispatch action filter event
        EventManager::instance()->dispatch(new Event('Action.' . $name, null, $data));
    }

    /**
     * @return array
     */
    public static function getThemes(): array
    {
        return array_filter(Plugin::loaded(), function ($pluginName) {
            return preg_match('/^Theme/', $pluginName) ? true : false;
        });
    }

    /**
     * @return array
     */
    public static function getSysDirs(): array
    {
        $dirs = [];

        // config dirs
        $dirs[] = CONFIG;
        $dirs[] = CONFIG . 'local';
        //$dirs[] = CONFIG . 'local' . DS . 'plugin';

        // cache dirs
        foreach (Cache::configured() as $name) {
            $engine = Cache::pool($name);
            if ($engine instanceof FileEngine) {
                $path = $engine->getConfig('path');
                $dirs[] = $path;
            } elseif ($engine instanceof DebugEngine) {
                if (!$engine->engine()) {
                    $engine->init();
                }
                $path = $engine->engine()->getConfig('path');
                $dirs[] = $path;
            }
        }

        // log dirs
        $dirs[] = LOGS;
        foreach (Log::configured() as $name) {
            $engine = Log::engine($name);
            if ($engine instanceof FileLog) {
                $dirs[] = $engine->getConfig('path');
            }
        }

        // tmp dirs
        $dirs[] = TMP;

        // cupcake specific
        $dirs[] = DATA_DIR;

        // shop dirs
        // payment dirs
        // other custom dirs

        $dirs = array_unique($dirs);

        return $dirs;
    }
}
