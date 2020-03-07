<?php
namespace Banana;

use Banana\Plugin\PluginInterface;
use Banana\Plugin\PluginRegistry;
use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\Utility\Security;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements EventDispatcherInterface
{
    use EventDispatcherTrait;

    protected static $_bootstrapped = false;

    /**
     * @var boolean
     */
    protected $_debug = false;

    /**
     * @param string $configDir Path to config directory
     */
    public function __construct($configDir)
    {
        parent::__construct($configDir);

        $this->_configEngine = new PhpConfig($this->configDir . DS);
        //$this->_plugins = new PluginRegistry();
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
        if (self::$_bootstrapped == true) {
            return;
        }
        self::$_bootstrapped = true;

        /*
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

        /*
         * Load path definitions
         */
        require_once $this->configDir . "/paths.php";

        /*
         * Bootstrap cake core
         */
        if (!defined('CORE_PATH')) {
            die('CORE_PATH is not defined');
        }
        require_once CORE_PATH . 'config' . DS . 'bootstrap.php';

        /*
         * Setup default config engine and load core configs
         */
        //try {
        Configure::config('default', $this->_configEngine);
        Configure::load('app', 'default', false);
        Configure::load('plugins');
        //} catch (\Cake\Core\Exception\Exception $ex) {
        //    die ($ex->getMessage());
        //} catch (\Exception $ex) {
        //    die ($ex->getMessage());
        //}

        /*
         * Common bootstrapping tasks
         */
        $this->_bootstrap();

        /*
         * Load and apply app configs
         */
        $this->_localConfigs();
        $this->_applyConfig();
        $this->_debugMode(Configure::read('debug'));
        /*
         * Load Banana plugin and other plugins defined in core config
         */
        $this->addPlugin('Banana');
        $this->addPlugin(Configure::read('Plugin'), ['bootstrap' => true, 'routes' => true]);

        /*
         * Init Banana
         */
        //$this->initLegacyPlugins();
        //$this->initRoutes();
        Banana::init($this);

        /*
         * Include app's bootstrap file
         */
        parent::bootstrap();
    }

    /**
     * Dynamically load plugin
     *
     * @param string $name Plugin anem
     * @param array $config Plugin config
     * @return $this
     */
    public function addPlugin($name, array $config = [])
    {
        if (is_array($name)) {
            foreach ($name as $_name => $_config) {
                if (is_numeric($_name)) {
                    $_name = $_config;
                    $_config = $config;
                }
                $this->addPlugin($_name, $_config);
            }

            return $this;
        }

        /*
        if (!Plugin::isLoaded($name)) {
            Plugin::load($name, ['bootstrap' => false, 'routes' => true, 'ignoreMissing' => true]);
        }

        if (!$this->plugins()->has($name)) {
            $Plugin = $this->plugins()->load($name, $config);

            if ($Plugin instanceof PluginInterface) {
                $Plugin->bootstrap($this);
            }
        }

        return $this;
        */

        return parent::addPlugin($name, $config);
    }

    /**
     * Get plugin info
     * @param string $pluginName Plugin name
     * @return array
     */
    public function getPluginInfo($pluginName)
    {
        $info = [];
        $info['name'] = $pluginName;
        $info['loaded'] = Plugin::isLoaded($pluginName);
        $info['path'] = Plugin::path($pluginName);
        $info['config'] = Plugin::configPath($pluginName);
        $info['classPath'] = Plugin::classPath($pluginName);
        //$info['registered'] = in_array($pluginName, Plugin::loaded());
        //$info['registered'] = true;
        $info['handler_loaded'] = $this->getPlugins()->has($pluginName);
        $info['handler_class'] = $this->getPlugins()->has($pluginName) ? get_class($this->getPlugins()->get($pluginName)) : null;
        $info['handler_bootstrap'] = $this->getPlugins()->has($pluginName) ? $this->getPlugins()->get($pluginName)->isEnabled('bootstrap') : null;
        $info['handler_routes'] = $this->getPlugins()->has($pluginName) ? $this->getPlugins()->get($pluginName)->isEnabled('routes') : null;
        //$info['handler_enabled'] = true;

        return $info;
    }

    /**
     * Get plugin registry instance
     * @deprecated Use getPlugins() instead. getPlugins() returns PluginCollection
     * @return PluginRegistry
     */
    public function plugins()
    {
        return $this->getPlugins();
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware($middlewareQueue)
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(null, Configure::read('Error')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime')
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance. For that when
            // creating the middleware instance specify the cache config name by
            // using it's second constructor argument:
            // `new RoutingMiddleware($this, '_cake_routes_')`
            ->add(new RoutingMiddleware($this));

        return $middlewareQueue;
    }
    /**
     * Auto-load local configurations
     */
    protected function _localConfigs()
    {
        // load config files from standard config directories
        foreach (['plugin', 'local', 'local/plugin'] as $dir) {
            if (!is_dir($this->configDir . DS . $dir)) {
                continue;
            }
            $files = scandir($this->configDir . DS . $dir);
            foreach ($files as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }

                if (preg_match('/^(.*)\.php$/', $file, $matches)) {
                    Configure::load($dir . '/' . $matches[1]);
                }
            }
        }
    }

    /**
     * Common bootstrap stuff
     */
    protected function _bootstrap()
    {
        // Set the full base URL.
        // This URL is used as the base of all absolute links.
        if (!Configure::read('App.fullBaseUrl')) {
            $s = null;
            if (env('HTTPS')) {
                $s = 's';
            }

            $httpHost = env('HTTP_HOST');
            if (isset($httpHost)) {
                Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
            }
            unset($httpHost, $s);
        }

        /*
         * Set server timezone to UTC. You can change it to another timezone of your
         * choice but using UTC makes time calculations / conversions easier.
         */
        date_default_timezone_set('UTC'); // @TODO Make default timezone configurable

        /*
         * Configure the mbstring extension to use the correct encoding.
         */
        mb_internal_encoding(Configure::read('App.encoding'));

        /*
         * Set the default locale. This controls how dates, number and currency is
         * formatted and sets the default language to use for translations.
         */
        ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

        /*
         * Setup detectors for mobile and tablet.
         * @todo Remove mobile request detectors from banana. Move to site's bootstrap
        Request::addDetector('mobile', function ($request) {
            $detector = new \Detection\MobileDetect();
            return $detector->isMobile();
        });
        Request::addDetector('tablet', function ($request) {
            $detector = new \Detection\MobileDetect();
            return $detector->isTablet();
        });
        */

        /*
         * Register database types
         */
        //Type::map('json', 'Banana\Database\Type\JsonType'); // obsolete since CakePHP 3.3
        Type::map('serialize', 'Banana\Database\Type\SerializeType');

        /*
         * Enable default locale format parsing.
         * This is needed for matching the auto-localized string output of Time() class when parsing dates.
         */
        Type::build('datetime')->useLocaleParser();

        $isCli = php_sapi_name() === 'cli';
        if ($isCli) {
            (new ConsoleErrorHandler(Configure::read('Error')))->register();

            // Include the CLI bootstrap overrides.
            require $this->configDir . '/bootstrap_cli.php';
            //} elseif (class_exists('\Gourmet\Whoops\Error\WhoopsHandler')) {
            // Out-of-the-box support for "Whoops for CakePHP3" by "gourmet"
            // https://github.com/gourmet/whoops
            //    (new \Gourmet\Whoops\Error\WhoopsHandler(Configure::read('Error')))->register();
        } else {
            (new ErrorHandler(Configure::read('Error')))->register();
        }
    }

    protected function _applyConfig()
    {
        /*
         * Consume configurations
         */
        //debug(Configure::read());
        ConnectionManager::setConfig(Configure::consume('Datasources'));
        Cache::setConfig(Configure::consume('Cache'));
        Log::setConfig(Configure::consume('Log'));
        Security::setSalt(Configure::consume('Security.salt'));
        TransportFactory::setConfig(Configure::consume('EmailTransport'));
        Email::setConfig(Configure::consume('Email'));
    }

    /**
     * Enable / Disable debug mode
     *
     * @param bool $enabled Debug mode flag
     * @return void
     */
    protected function _debugmode($enabled)
    {
        if ($enabled) {
            if (Configure::read('DebugKit.enabled')) {
                // disable Mail panel by default, as it doesn't play nice with Mailman plugin
                // @TODO Play nice with DebugKit's Mail preview
                if (!Configure::check('DebugKit.panels')) {
                    Configure::write('DebugKit.panels', ['DebugKit.Mail' => false]);
                }

                try {
                    $this->addPlugin('DebugKit');
                } catch (\Exception $ex) {
                    //debug("DebugKit: " . $ex->getMessage());
                }
            }
        } else {
            // When debug = false the metadata cache should last
            // for a very very long time, as we don't want
            // to refresh the cache while users are doing requests.
            Configure::write('Cache._cake_model_.duration', '+1 years');
            Configure::write('Cache._cake_core_.duration', '+1 years');
        }
    }

    /**
     * Load the plugin handler for all loaded plugins.
     * Uses reflection on the Cake's Plugin class to read the plugin config.
     * Automatically passes plugin config
     *
     * @return void
     */
    protected function initLegacyPlugins()
    {
        $r = new \ReflectionClass('\\Cake\\Core\\Plugin');
        $sProps = $r->getStaticProperties();
        $loadedPlugins = (isset($sProps['_plugins'])) ? $sProps['_plugins'] : [];
        //debug($loadedPlugins);

        //foreach (Plugin::loaded() as $name) {
        foreach ($loadedPlugins as $name => $config) {
            //$pluginConfig = Configure::read($name);
            //$config['config'] = (array)$pluginConfig;
            //debug($config);
            try {
                //$this->plugins()->load($name, $config);
                $this->addPlugin($name, $config);
            } catch (\Exception $ex) {
                Log::error('Application: ' . $ex->getMessage());
            }
        }
    }

    /**
     * Initialize plugin routes
     *
     * @return void
     */
    protected function initRoutes()
    {
        Router::routes(); // Make sure app 'routes.php' is loaded

        foreach ($this->plugins()->loaded() as $name) {
            $instance = $this->plugins()->get($name);
            if ($instance instanceof PluginInterface) {
                Router::plugin(
                    $name,
                    [
                        'plugin' => $name,
                        //'path' => '/' . Inflector::underscore($name),
                        //'_namePrefix' => Inflector::underscore($name) . ':'
                    ],
                    [$instance, 'routes']
                );
            }
        }
    }

    /**
     * Initialize plugin middlewares
     *
     * @param MiddlewareQueue $middleware The middleware stack
     * @return MiddlewareQueue
     */
    protected function initMiddleware($middleware)
    {
        foreach ($this->plugins()->loaded() as $name) {
            $instance = $this->plugins()->get($name);
            if ($instance instanceof PluginInterface) {
                $_middleware = $instance->middleware($middleware);
                if ($_middleware) {
                    $middleware = $_middleware;
                }
            }
        }

        return $middleware;
    }
}
