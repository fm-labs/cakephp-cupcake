<?php
declare(strict_types=1);

namespace Cupcake;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Core\PluginCollection;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\BaseApplication;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\Router;
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

    /**
     * @var \Cake\Core\Configure\ConfigEngineInterface
     */
    private $_configEngine;

    /**
     * @param string $configDir Path to config directory
     */
    public function __construct($configDir)
    {
        parent::__construct($configDir);

        $this->_configEngine = new PhpConfig($this->configDir . DS);
    }

    /**
     * Load all the application configuration and bootstrap logic.
     *
     * Override this method to add additional bootstrap logic for your application.
     *
     * @return void
     * @throws \Exception
     */
    public function bootstrap(): void
    {
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
         * - load cupcake plugin
         * - setup cupcake (init plugin- and settings- manager)
         * - bootstrap cupcake
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
        Configure::config('default', $this->_configEngine);
        Configure::load('app', 'default', false);
        if (file_exists(CONFIG . 'app_local.php')) {
            Configure::load('app_local', 'default');
        }
        if (file_exists(CONFIG . 'plugins.php')) {
            Configure::load('plugins', 'default');
        }

        /*
         * Common bootstrapping tasks
         */
        $this->_bootstrap();

        /*
         * Load and apply app configs
         */
        $this->_localConfigs();
        $this->_applyConfig();

        /*
         * Load Cupcake plugin and other plugins defined in core config
         */
        $this->addPlugin('Cupcake');
        $this->addPlugin(Configure::read('Plugin'), ['bootstrap' => true, 'routes' => true]);

        /*
         * Debug mode
         */
        $this->_debugMode(Configure::read('debug'));

        /*
         * Init Cupcake
         */
        Cupcake::init($this);

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

        $plugin = $this->getPlugins()->has($pluginName) ? $this->getPlugins()->get($pluginName) : null;

        $info['handler_loaded'] = $plugin ? true : false;
        $info['handler_class'] = $plugin ? get_class($plugin) : null;
        $info['handler_bootstrap'] = $plugin ? $plugin->isEnabled('bootstrap') : null;
        $info['handler_routes'] = $plugin ? $plugin->isEnabled('routes') : null;
        //$info['handler_enabled'] = true;
        //$info['configuration_url'] = $plugin && $plugin instanceof BasePlugin ? $plugin->getConfigurationUrl() : null;
        $info['configuration_url'] = null;

        return $info;
    }

    /**
     * Get plugin registry instance
     * @deprecated Use getPlugins() instead. getPlugins() returns PluginCollection
     * @return \Cake\Core\PluginCollection
     */
    public function plugins(): PluginCollection
    {
        return $this->getPlugins();
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware($middlewareQueue): \Cake\Http\MiddlewareQueue
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
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
     * Auto-load local configurations.
     *
     * @return void
     */
    protected function _localConfigs(): void
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
     *
     * @return void
     */
    protected function _bootstrap(): void
    {
        /**
         * Set server timezone to UTC. You can change it to another timezone of your
         * choice but using UTC makes time calculations / conversions easier.
         */
        date_default_timezone_set(Configure::read('App.defaultTimezone', 'UTC'));

        /**
         * Configure the mbstring extension to use the correct encoding.
         */
        mb_internal_encoding(Configure::read('App.encoding', 'UTF-8'));

        /**
         * Set the default locale. This controls how dates, number and currency is
         * formatted and sets the default language to use for translations.
         */
        ini_set('intl.default_locale', Configure::read('App.defaultLocale', 'en'));

        /**
         * CLI
         */
        $isCli = php_sapi_name() === 'cli';
        if ($isCli) {
            (new \Cake\Error\ConsoleErrorHandler(Configure::read('Error')))->register();

            // Include the CLI bootstrap overrides.
            require $this->configDir . '/bootstrap_cli.php';
            //} elseif (class_exists('\Gourmet\Whoops\Error\WhoopsHandler')) {
            // Out-of-the-box support for "Whoops for CakePHP3" by "gourmet"
            // https://github.com/gourmet/whoops
            //    (new \Gourmet\Whoops\Error\WhoopsHandler(Configure::read('Error')))->register();
        } else {
            (new ErrorHandler(Configure::read('Error')))->register();
        }

        /**
         * Set the full base URL.
         * This URL is used as the base of all absolute links.
         */
        $fullBaseUrl = Configure::read('App.fullBaseUrl');
        if (!$fullBaseUrl) {
            $s = null;
            if (env('HTTPS')) {
                $s = 's';
            }

            $httpHost = env('HTTP_HOST');
            if (isset($httpHost)) {
                $fullBaseUrl = 'http' . $s . '://' . $httpHost;
            }
            unset($httpHost, $s);
        }
        if ($fullBaseUrl) {
            Router::fullBaseUrl($fullBaseUrl);
        }
    }

    /**
     * Apply system configurations.
     *
     * @return void
     */
    protected function _applyConfig(): void
    {
        Cache::setConfig(Configure::consume('Cache'));
        ConnectionManager::setConfig(Configure::consume('Datasources'));
        TransportFactory::setConfig(Configure::consume('EmailTransport'));
        Mailer::setConfig(Configure::consume('Email'));
        Log::setConfig(Configure::consume('Log'));
        Security::setSalt(Configure::consume('Security.salt'));

        /**
         * Setup detectors for mobile and tablet.
         */
        ServerRequest::addDetector('mobile', function ($request) {
            $detector = new \Detection\MobileDetect();

            return $detector->isMobile();
        });
        ServerRequest::addDetector('tablet', function ($request) {
            $detector = new \Detection\MobileDetect();

            return $detector->isTablet();
        });

        /**
         * Register database types
         */
        //\Cake\Database\TypeFactory::map('json', 'Cupcake\Database\Type\JsonType'); // obsolete since CakePHP 3.3
        \Cake\Database\TypeFactory::map('serialize', 'Cupcake\Database\Type\SerializeType');

        /**
         * Enable default locale format parsing.
         * This is needed for matching the auto-localized string output of Time() class when parsing dates.
         */
        //\Cake\Database\TypeFactory::build('datetime')->useLocaleParser();
    }

    /**
     * Enable / Disable debug mode
     *
     * @param bool $enabled Debug mode flag
     * @return void
     */
    protected function _debugmode($enabled): void
    {
        if ($enabled) {
            if (Configure::read('DebugKit.enabled')) {
                // disable Mail panel by default, as it doesn't play nice with Mailman plugin
                // @TODO Play nice with DebugKit's Mail preview
                //if (!Configure::check('DebugKit.panels')) {
                //    Configure::write('DebugKit.panels', ['DebugKit.Mail' => false]);
                //}

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
}
