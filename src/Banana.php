<?php

namespace Banana;

use Backend\Backend;
use Banana\Plugin\PluginInterface;
use Banana\Plugin\PluginManager;
use Cake\Core\Configure;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\BaseApplication;
use Cake\Routing\Router;
use Settings\SettingsManager;

/**
 * Class Banana
 *
 * @package Banana
 * @todo Refactor as (service) container
 * @todo Caching service
 * @todo Log service
 */
class Banana implements EventDispatcherInterface
{
    use EventDispatcherTrait;

    /**
     * @var string Default mailer class
     */
    static public $mailerClass = 'Cake\Mailer\Mailer';

    /**
     * List of Banana instances. Singleton holder.
     */
    static protected $_instances = [];

    /**
     * @var BaseApplication
     */
    protected $_app;

    /**
     * @var PluginManager
     */
    protected $_pluginManager;

    /**
     * @var SettingsManager
     */
    protected $_settingsManager;

    /**
     * @var Backend
     */
    protected $_backend;

    /**
     * @var array
     */
    static protected $_plugins = [];

    /**
     * Banana-app wide common mailer instance
     *
     * @return \Cake\Mailer\Mailer
     */
    static public function getMailer()
    {
        return new self::$mailerClass();
    }

    /**
     * Register a plugin handler
     */
    static public function plugin($plugin, $handler)
    {
        static::$_plugins[$plugin] = $handler;
    }

    /**
     * Singleton getter
     * @return Banana
     * @throws \Exception
     */
    static public function init(Application $app)
    {
        if (isset(self::$_instances[0])) {
            throw new \Exception('Banana::init: Already initialized');
        }

        return self::$_instances[0] = new self($app);
    }

    /**
     * Singleton getter
     * @return Banana
     * @throws \Exception
     */
    static public function getInstance()
    {
        if (!isset(self::$_instances[0])) {
            throw new \Exception('Banana::getInstance: Not initialized');
        }
        return self::$_instances[0];
    }

    /**
     * Singleton instance constructor
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->_app = $app;

//        // bootstrap plugins
//        foreach ($this->pluginManager()->enabled() as $pluginName) {
//            $plugin = $this->pluginManager()->get($pluginName);
//            if ($plugin instanceof PluginInterface) {
//                $plugin->bootstrap($this->_app);
//            }
//        }

        //Router::routes(); // <-- required workaround. need to call routes() first, otherwise all existing routes are vanished
        foreach (static::$_plugins as $plugin => $handler) {
            // bootstrap plugin handler
            if ($handler instanceof PluginInterface) {
                //$handler->bootstrap($this->_app);
            }

            // plugin handler routes

            //Router::scope(static::$urlPrefix, ['prefix' => 'admin'], function(RouteBuilder $routes) {
            //    EventManager::instance()->dispatch(new \Backend\Event\RouteBuilderEvent('Backend.Routes.build', $routes));
            //});
            //Router::plugin($plugin, [], [$handler, 'routes']);
        }

    }

    public function run()
    {
    }

    /**
     * @deprecated
     */
    public function runBackend()
    {
        //$this->eventManager()->on($this->backend());
        //$this->dispatchEvent('Backend.startup', [], $this);
        $this->backend()->run();
    }

    /**
     * Get / Set application instance
     * @param Application $app
     * @return Application
     * @deprecated
     */
    public function application(Application $app = null)
    {
        if ($app !== null) {
            $this->_app = $app;
        }
        return $this->_app;
    }

    /**
     * Get / Set plugin mananager instance
     * @param PluginManager $pluginManager
     * @return PluginManager
     * @deprecated
     */
    public function pluginManager(PluginManager $pluginManager = null)
    {
        return $this->_app->plugins();
    }

    /**
     * Get / Set settings mananager instance
     * @param SettingsManager $settingsManager
     * @return SettingsManager
     * @deprecated
     */
    public function settingsManager(SettingsManager $settingsManager = null)
    {
        return $this->_app->settings();
    }

    /**
     * Get Backend instance
     * @return Backend
     * @deprecated
     */
    public function backend()
    {
        return $this->_app->backend();
    }

    /**
     * Static direct accessor to plugin handler
     * @return object|null
     * @deprecated
     */
    //static public function Plugin($pluginName)
    //{
    //    return self::getInstance()->pluginManager()->get($pluginName);
    //}

    /**
     * Static direct accessor to Application instance
     * @return Application
     * @deprecated
     */
    static public function App()
    {
        return self::getInstance()->application();
    }
}
