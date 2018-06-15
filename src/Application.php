<?php
namespace Banana;

use Backend\Backend;
use Backend\Routing\Middleware\BackendMiddleware;
use Banana\Plugin\PluginManager;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\ConfigEngineInterface;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Database\Type;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Event\Event;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cake\Http\BaseApplication;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Utility\Security;
use Settings\SettingsManager;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements EventDispatcherInterface
{
    use EventDispatcherTrait;

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
     * @param string $configDir
     */
    public function __construct($configDir)
    {
        parent::__construct($configDir);

        //$this->_pluginManager = new PluginManager($this->eventManager());
        //$this->_settingsManager = new SettingsManager();
    }

    /**
     * Load all the application configuration and bootstrap logic.
     *
     * Override this method to add additional bootstrap logic for your application.
     *
     * @return void
     */
    public function bootstrap()
    {
        /**
         * NOW: ENTERING RUNLEVEL 1 (BOOTSTRAPPING)
         * - setup paths
         * - bootstrap cake core
         * - setup default config engine
         * - load configurations
         * - setup full base url in configuration
         * - configure: timezone, encoding, locale, error handler
         * - include user's bootstrap file
         * - configure: request detectors, database types, debugmode
         * - consume configurations: ConnectionManager, Cache, Email, Log, Security
         * - load banana plugin
         * - setup banana (init plugin- and settings- manager)
         * - bootstrap banana
         *
         */

        /**
         * Load path definitions
         */
        require_once $this->configDir . "/paths.php";

        /**
         * Bootstrap cake core
         */
        if (!defined('CORE_PATH')) {
            die('CORE_PATH is not defined. [SITE ID: ' . BC_SITE_ID . ']');
        }
        require CORE_PATH . 'config' . DS . 'bootstrap.php';

        /**
         * Setup default config engine and load configs
         */
        //try {
        Configure::config('default', $this->getDefaultConfigEngine());
        $this->_loadConfigs();
        //} catch (\Cake\Core\Exception\Exception $ex) {
        //    die ($ex->getMessage());
        //} catch (\Exception $ex) {
        //    die ($ex->getMessage());
        //}

        /**
         * Load Banana plugin (handles default bootstrap behavior)
         */
        Plugin::load('Banana', ['bootstrap' => true, 'routes' => false]);

        /**
         * Bootstrap site
         */
        //require_once $this->configDir . '/bootstrap.php';
        parent::bootstrap();


        /**
         * Debug mode
         */
        $this->setDebugMode(Configure::read('debug'));


        /**
         * Consume configurations
         */
        //debug(Configure::read());
        ConnectionManager::config(Configure::consume('Datasources'));
        Cache::config(Configure::consume('Cache'));
        Log::config(Configure::consume('Log'));
        Security::salt(Configure::consume('Security.salt'));
        Email::configTransport(Configure::consume('EmailTransport'));
        Email::config(Configure::consume('Email'));



        /**
         * At this point:
         * Path constants have been set
         * Cake's bootstrap code executed (start timer, load functions, ...)
         * App configurations have been loaded
         * Configuration settings for locale, time and encoding have been set
         * App's bootstrap executed
         * Debug mode configured
         * Configurations consumed by components
         *
         * NOW: ENTERING NEXT RUN LEVEL 2 (PLUGIN LOADING)
         */

        //PluginManager::config(Configure::read('Banana.plugins'));
        //PluginManager::loadAll();


        //debug(Plugin::loaded());
        //debug(Configure::read());

        /**
         * Load plugins
         */
        //new PluginManager(Configure::consume('Banana.plugins'));

        // Load and Init Banana runtime
        $this->plugins()->load([
            'Banana'    => ['bootstrap' => true, 'routes' => false],

            'Settings'  => ['bootstrap' => true, 'routes' => true],
            'User'      => ['bootstrap' => true, 'routes' => true],
            'Backend'   => ['bootstrap' => true, 'routes' => true]
        ], [], true);

        // Load and enable plugins configured in plugins.php
        $plugins = (array) Configure::read('Banana.plugins')
            + (array) Configure::read('Plugin'); // legacy
        $this->plugins()->load($plugins, [], true);

        // Load all other available plugins the cake way,
        // to make them visible, but with bootstrap and routes configs disabled
        Plugin::loadAll(['bootstrap' => false, 'routes' => false, 'ignoreMissing' => true]);

        Banana::init($this);
        //$this->plugins()->bootstrap($this); // bootstrap enabled plugins
        //$this->eventManager()->dispatch(new Event('Application.bootstrap', $this));
    }

    /**
     * Get default config engine
     * Override in sub-classes to change default config engine
     *
     * @return ConfigEngineInterface
     */
    protected function getDefaultConfigEngine()
    {
        return new PhpConfig($this->configDir . DS);
    }

    /**
     * Sub-routine to auto-load configurations
     * Override in sub-classes to change config loading behavior
     */
    protected function _loadConfigs()
    {
        // app config
        Configure::load('app', 'default', false);
        Configure::load('plugins');
        Configure::load('site'); //@TODO Remove dependency on this file

        // beta config overrides
        if (defined('ENV_BETA')) { // @TODO Replace with environment configs
            Configure::load('beta');
            Configure::write('App.beta', ENV_BETA);
        }

        // local config overrides
        try { Configure::load('local/app'); } catch(\Exception $ex) {}
        try { Configure::load('local/cake-plugins'); } catch(\Exception $ex) {}
    }


    /**
     * Enables / Disables debug mode
     * Override in sub-classes to change debug mode behavior
     */
    public function setDebugMode($enabled = true)
    {
        if ($enabled) {
            // disable Mail panel by default, as it doesn't play nice with Mailman plugin
            // @TODO Play nice with DebugKit
            //if (!Configure::check('DebugKit.panels')) {
            //    Configure::write('DebugKit.panels', ['DebugKit.Mail' => false]);
            //}

            try {
                Plugin::load('DebugKit', ['bootstrap' => true, 'routes' => true]);
            } catch (\Exception $ex) {
                debug("DebugKit: " . $ex->getMessage());
            }

        } else {
            // When debug = false the metadata cache should last
            // for a very very long time, as we don't want
            // to refresh the cache while users are doing requests.
            Configure::write('Cache._cake_model_.duration', '+1 years');
            Configure::write('Cache._cake_core_.duration', '+1 years');
        }
    }

    public function eventManager(EventManager $eventManager = null)
    {
        if (!$this->_eventManager) {
            $this->_eventManager = EventManager::instance();
        }
        return $this->_eventManager;
    }

    /**
     * Get plugin mananager instance
     * @return PluginManager
     */
    public function plugins()
    {
        if (!$this->_pluginManager) {
            $this->_pluginManager = new PluginManager($this->eventManager());
        }
        return $this->_pluginManager;
    }

    /**
     * Get settings mananager instance
     * @return SettingsManager
     */
    public function settings()
    {
        if (!$this->_settingsManager) {
            $this->_settingsManager = new SettingsManager();
        }
        return $this->_settingsManager;
    }

    /**
     * Get Backend instance
     * @return Backend
     */
    public function backend()
    {
        if (!$this->_backend) {
            $this->_backend = new Backend();
        }
        return $this->_backend;
    }


    /**
     * Setup the middleware your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middleware The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware.
     */
    public function middleware($middleware)
    {
        $middleware
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error.exceptionRenderer')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware())

            // Auto-wire banana plugins
            //->add(new BananaMiddleware())
            ->add(new BackendMiddleware())

            // Apply routing
            ->add(new RoutingMiddleware());


        return $middleware;
    }
}
