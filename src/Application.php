<?php
namespace Banana;

use Backend\Backend;
use Backend\Routing\Middleware\BackendMiddleware;
use Banana\Plugin\PluginInterface;
use Banana\Plugin\PluginManager;
use Banana\Plugin\PluginRegistry;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\ConfigEngineInterface;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Database\Type;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cake\Http\BaseApplication;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\RouteCollection;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Cake\Console\ConsoleErrorHandler;
use Cake\Error\ErrorHandler;
use Cake\Network\Request;

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
     * @var Backend
     */
    protected $_backend;

    /**
     * @var boolean
     */
    protected $_debug = false;

    /**
     * @param string $configDir
     */
    public function __construct($configDir)
    {
        parent::__construct($configDir);

        //$this->_pluginManager = new PluginManager($this->eventManager());
        $this->_configEngine = new PhpConfig($this->configDir . DS);
        $this->_plugins = new PluginRegistry();
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
        Configure::config('default', $this->_configEngine);
        Configure::load('app', 'default', false);
        Configure::load('plugins');
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
        $this->_debugMode(Configure::read('debug'));
        $this->_bootstrap();

        //parent::bootstrap();
        require_once $this->configDir . '/bootstrap.php';

        $this->_localConfigs();
        $this->_applyConfig();

        Banana::init($this);
        Plugin::load(Configure::read('Plugin'), ['bootstrap' => true, 'routes' => true, 'ignoreMissing' => true]);
        $this->_pluginsLoad();
        $this->_pluginsBootstrap();
        $this->_pluginsRoutes();

    }


    public function addPlugin($name, array $config)
    {
        $this->plugins()->load($name, $config);
        return $this;
    }

    /**
     * Get plugin info
     * @return array
     */
    public function getPluginInfo($pluginName)
    {
        $info = [];
        $info['name'] = $pluginName;
        $info['loaded'] = Plugin::loaded($pluginName);
        $info['path'] = Plugin::path($pluginName);
        $info['config'] = Plugin::configPath($pluginName);
        $info['classPath'] = Plugin::classPath($pluginName);
        //$info['registered'] = in_array($pluginName, Plugin::loaded());
        $info['registered'] = true;
        $info['handler_loaded'] = $this->plugins()->has($pluginName);
        $info['handler_class'] = get_class($this->plugins()->get($pluginName));
        $info['handler_enabled'] = true;

        return $info;
    }

    /**
     * Get plugin registry instance
     * @return PluginRegistry
     */
    public function plugins()
    {
        return $this->_plugins;
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
            ->add(new BackendMiddleware($this))

            // Apply routing
            ->add(new RoutingMiddleware());

        $this->_pluginsMiddleware($middleware);

        return $middleware;
    }

    /**
     * Auto-load local configurations
     */
    protected function _localConfigs()
    {
        // load config files from standard config directories
        foreach (['plugin', 'local', 'local/plugin'] as $dir) {
            if (!is_dir($this->configDir . DS . $dir)) continue;
            $files = scandir($this->configDir . DS . $dir);
            foreach ($files as $file) {
                if ($file == '.' || $file == '..') continue;

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


        /**
         * Set server timezone to UTC. You can change it to another timezone of your
         * choice but using UTC makes time calculations / conversions easier.
         */
        date_default_timezone_set('UTC'); // @TODO Make default timezone configurable

        /**
         * Configure the mbstring extension to use the correct encoding.
         */
        mb_internal_encoding(Configure::read('App.encoding'));

        /**
         * Set the default locale. This controls how dates, number and currency is
         * formatted and sets the default language to use for translations.
         */
        ini_set('intl.default_locale', Configure::read('App.defaultLocale'));


        /**
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

        /**
         * Register database types
         */
        //Type::map('json', 'Banana\Database\Type\JsonType'); // obsolete since CakePHP 3.3
        Type::map('serialize', 'Banana\Database\Type\SerializeType');

        /**
         * Enable default locale format parsing.
         * This is needed for matching the auto-localized string output of Time() class when parsing dates.
         */
        Type::build('datetime')->useLocaleParser();


        $isCli = php_sapi_name() === 'cli';
        if ($isCli) {
            (new ConsoleErrorHandler(Configure::read('Error')))->register();

            // Include the CLI bootstrap overrides.
            //require $this->configDir . '/bootstrap_cli.php';
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
    }

    protected function _debugmode($enabled)
    {
        if ($enabled) {
            // disable Mail panel by default, as it doesn't play nice with Mailman plugin
            // @TODO Play nice with DebugKit
            //if (!Configure::check('DebugKit.panels')) {
            //    Configure::write('DebugKit.panels', ['DebugKit.Mail' => false]);
            //}

            /*
            try {
                Plugin::load('DebugKit', ['bootstrap' => true, 'routes' => true]);
            } catch (\Exception $ex) {
                debug("DebugKit: " . $ex->getMessage());
            }
            */

        } else {
            // When debug = false the metadata cache should last
            // for a very very long time, as we don't want
            // to refresh the cache while users are doing requests.
            Configure::write('Cache._cake_model_.duration', '+1 years');
            Configure::write('Cache._cake_core_.duration', '+1 years');
        }
    }

    /**
     * Load the plugin handler for all loaded plugins
     */
    protected function _pluginsLoad()
    {
        foreach (Plugin::loaded() as $name) {
            $pluginConfig = Configure::read($name);
            try {
                $this->plugins()->load($name, $pluginConfig);
            } catch (\Exception $ex) {
                Log::error('Application: ' . $ex->getMessage());
            }
        }
    }

    protected function _pluginsBootstrap()
    {
        foreach ($this->plugins()->loaded() as $name) {
            $instance = $this->plugins()->get($name);
            if ($instance instanceof PluginInterface) {
                $instance->bootstrap($this);
            }
        }
    }

    protected function _pluginsRoutes()
    {
        foreach ($this->plugins()->loaded() as $name) {
            $instance = $this->plugins()->get($name);
            if ($instance instanceof PluginInterface) {
                Router::plugin($name, ['plugin' => $name], [$instance, 'routes']);
            }
        }
    }

    protected function _pluginsMiddleware($middleware)
    {
        foreach ($this->plugins()->loaded() as $name) {
            $instance = $this->plugins()->get($name);
            if ($instance instanceof PluginInterface) {
                $instance->middleware($middleware);
            }
        }
    }


}
