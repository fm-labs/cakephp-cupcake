<?php
declare(strict_types=1);

namespace Cupcake;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\Core\PluginCollection;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\Utility\Security;
use Cupcake\Configure\Engine\LocalPhpConfig;

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
    private Configure\ConfigEngineInterface $_configEngine;

    /**
     * @param string $configDir Path to config directory
     */
    public function __construct($configDir)
    {
        parent::__construct($configDir);

        $this->_configEngine = new LocalPhpConfig($this->configDir);
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
        /**
         * NOW: ENTERING RUNLEVEL 1 (BOOTSTRAPPING)
         * - setup paths
         * - bootstrap cake core
         * - setup default config engine
         * - load app config
         * - load plugins config
         * - load local configurations
         * - setup full base url in configuration
         * - configure: timezone, encoding, locale, error handler
         * - include user's bootstrap file
         * - configure: request detectors, database types, debugmode
         * - consume configurations: ConnectionManager, Cache, Email, Log, Security
         * - load cupcake plugin
         * - bootstrap cupcake
         *
         */

        /**
         * Include app's bootstrap file
         */
        if (file_exists($this->configDir . 'bootstrap.php')) {
            //require_once $this->configDir . 'bootstrap.php';
            parent::bootstrap();
        }

        /**
         * Check system requirements
         */
        $this->_checkRequirements();

        /**
         * Load path definitions
         */
        if (file_exists($this->configDir . 'paths.php')) {
            require_once $this->configDir . 'paths.php';
        }
        $this->_paths();

        /**
         * Bootstrap cake core
         */
        if (!defined('CORE_PATH')) {
            die('CORE_PATH is not defined');
        }
        require_once CORE_PATH . 'config' . DS . 'bootstrap.php';

        /**
         * Setup default config engine and load core configs
         */
        Configure::config('default', $this->_configEngine);
        Configure::load('app', 'default', false);
        if (file_exists(CONFIG . 'app_local.php')) {
            Configure::load('app_local', 'default');
        }
        if (file_exists(CONFIG . 'local' . DS . 'app.php')) {
            Configure::load('local/app', 'default');
        }

        /**
         * Load configuration files
         */
        //$this->_loadConfigs();

        /**
         * Debug mode.
         * Must be run before boostrap, to be able to patch configuration values before they get consumed.
         */
        $this->_debugMode(Configure::read('debug'));

        /**
         * Cli
         */
        if (php_sapi_name() === 'cli') {
            $this->_bootstrapCli();
        } else {
            //@todo Enable FactoryLocator in bootstrap
            //@link https://github.com/cakephp/app/blob/4.x/src/Application.php
//            FactoryLocator::add(
//                'Table',
//                (new TableLocator())->allowFallbackClass(false)
//            );
        }

        /**
         * Common bootstrapping tasks
         */
        $this->_bootstrap();

        /**
         * Load core plugins and user plugins
         */
        if (file_exists(CONFIG . 'plugins.php')) {
            Configure::load('plugins');
        }
        $this->addPlugin('Cupcake');
        $this->addPlugin((array)Configure::read('Plugin')/*, ['bootstrap' => true, 'routes' => true]*/);

        /**
         * CakePHP DebugKit support
         */
        if (Configure::read('DebugKit.enabled')) {
            $this->addOptionalPlugin('DebugKit');
        }

        /*
         * Init Cupcake
         */
        Cupcake::init($this);

        /*
         * Add cupcake templates path as fallback template search path
         */
        $templatePaths = Configure::read('App.paths.templates', []);
        $templatePaths[] = \Cake\Core\Plugin::templatePath('Cupcake');
        Configure::write('App.paths.templates', $templatePaths);
    }

    /**
     * Bootstrap plugins
     *
     * @override
     * @return void
     */
    public function pluginBootstrap(): void
    {
        //parent::pluginBootstrap();
        foreach ($this->plugins->with('bootstrap') as $plugin) {
            //\Cupcake\Cupcake::doAction('plugin_bootstrap', compact('plugin'));
            try {
                //Configure::load($plugin->getName() . '.' . Inflector::underscore($plugin->getName()));
                //$this->loadPluginConfig($plugin->getName());
                $plugin->bootstrap($this);
            } catch (\Exception $ex) {
                debug($ex->getMessage());
            }

            //\Cupcake\Cupcake::doAction('plugin_ready', compact('plugin'));
        }

        //\Cupcake\Cupcake::doAction('app_ready', compact('plugin'));
    }

    /**
     * Only try to load routes from config file, if the file is present.
     * The CakePHP Application enforces the presence of a routes.php file.
     * The Cupcake Application ignores the absence gracefully.
     *
     * @param RouteBuilder $routes
     * @return void
     */
    public function routes(RouteBuilder $routes): void
    {
        $routesFilePath = $this->configDir . 'routes.php';
        if (is_file($routesFilePath)) {
            parent::routes($routes);
        }
    }

    /**
     * Autoload plugin configs from standard directories
     *
     * @param string $plugin Plugin name
     * @throws \Exception
     * @deprecated Use LocalPhpConfig instead.
     */
    public function loadPluginConfig(string $plugin): void
    {
        deprecationWarning("Application::loadPluginConfig() is deprecated. Do not use.");

        $file = Inflector::underscore($plugin);

        // first try to load the default plugin configuration from the plugin
        $filePath = Plugin::configPath($plugin) . DS . $file . '.php';
        if (file_exists($filePath)) {
            if (!Configure::load($plugin . '.' . $file, 'default', true)) {
                throw new \RuntimeException('Failed to load config file ' . $file);
            }
        }

        foreach (['plugin', 'local/plugin'] as $dir) {
            $filePath = $this->configDir . DS . $dir . DS . $file . '.php';
            if (file_exists($filePath)) {
                if (!Configure::load($dir . '/' . $file, 'default', true)) {
                    throw new \RuntimeException('Failed to load config file ' . $file);
                }
            }
        }

        if (Configure::isConfigured('settings')) {
            Configure::load($plugin, 'settings');
        }
    }

    /**
     * Dynamically load plugin
     *
     * @override
     * @param \Cake\Core\PluginInterface|string|array $name Plugin name
     * @param array $config Plugin config
     * @return $this
     */
    public function addPlugin($name, array $config = []): Application
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

        $bootstrap = $config['bootstrap'] ?? true;
        if (!$bootstrap) {
            //debug("Skipping disabled plugin: $name");
            return $this;
        }

        return parent::addPlugin($name, $config);
    }

    /**
     * @override
     * @param \Cake\Core\PluginInterface|string|array $name Plugin name
     * @param array $config Plugin config
     * @return $this
     */
    public function addOptionalPlugin($name, array $config = []): Application
    {
        try {
            $this->addPlugin($name, $config);
        } catch (MissingPluginException $ex) {
            // ignore missing plugin
        }

        return $this;
    }

    /**
     * Get plugin info
     *
     * @param string $pluginName Plugin name
     * @return array
     */
    public function getPluginInfo(string $pluginName): array
    {
        deprecationWarning("Application::getPluginInfo() is deprecated. Use PluginManager::getPluginInfo() instead.");

        return \Cupcake\PluginManager::getPluginInfo($pluginName);
    }

    /**
     * Get plugin registry instance
     *
     * @deprecated Use getPlugins() instead. getPlugins() returns PluginCollection
     * @return \Cake\Core\PluginCollection
     */
    public function plugins(): PluginCollection
    {
        deprecationWarning('Application::plugins() is deprecated. Use getPlugins() instead.');

        return $this->getPlugins();
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(\Cake\Http\MiddlewareQueue $middlewareQueue): \Cake\Http\MiddlewareQueue
    {
        $routingCacheConfig = (bool)Configure::read('debug') || (bool)Configure::read('Routing.disabled') === true
            ? null : Configure::read('Routing.cacheConfigName', '_cake_routes_');

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
            ->add(new RoutingMiddleware($this, $routingCacheConfig))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware());

//            // Cross Site Request Forgery (CSRF) Protection Middleware
//            // https://book.cakephp.org/4/en/security/csrf.html#cross-site-request-forgery-csrf-middleware
//            ->add(new CsrfProtectionMiddleware([
//                'httponly' => true,
//            ]));

        return $middlewareQueue;
    }

    /**
     * Setup path constants
     *
     * @return void
     */
    protected function _paths(): void
    {
        defined('DS') || define('DS', DIRECTORY_SEPARATOR);
        defined('ROOT') || define('ROOT', dirname($this->configDir));
        defined('APP_DIR') || define('APP_DIR', 'src');
        defined('APP') || define('APP', ROOT . DS . APP_DIR . DS);
        defined('CONFIG') || define('CONFIG', ROOT . DS . 'config' . DS);
        defined('WWW_ROOT') || define('WWW_ROOT', ROOT . DS . 'webroot' . DS);
        defined('TESTS') || define('TESTS', ROOT . DS . 'tests' . DS);
        defined('TMP') || define('TMP', ROOT . DS . 'tmp' . DS);
        defined('LOGS') || define('LOGS', ROOT . DS . 'logs' . DS);
        defined('CACHE') || define('CACHE', TMP . 'cache' . DS);
        defined('RESOURCES') || define('RESOURCES', ROOT . DS . 'resources' . DS);
        defined('CAKE_CORE_INCLUDE_PATH') || define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');
        defined('CORE_PATH') || define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
        defined('CAKE') || define('CAKE', CORE_PATH . 'src' . DS);

        // non cake standard paths:
        defined('CC_CORE_PATH') || define('CC_CORE_PATH', dirname(__DIR__) . DS);
        defined('DATA') || define('DATA', ROOT . DS . 'data' . DS);

        // legacy:
        defined('DATA_DIR') || define('DATA_DIR', DATA);
    }

//    /**
//     * Auto-load local configurations.
//     *
//     * @return void
//     * @deprecated
//     */
//    protected function _loadAllConfigs(): void
//    {
//        // load config files from standard config directories
//        foreach (['plugin', 'local', 'local/plugin'] as $dir) {
//            if (!is_dir($this->configDir . DS . $dir)) {
//                continue;
//            }
//            $files = scandir($this->configDir . DS . $dir);
//            foreach ($files as $file) {
//                if ($file == '.' || $file == '..') {
//                    continue;
//                }
//
//                if (preg_match('/^(.*)\.php$/', $file, $matches)) {
//                    Configure::load($dir . '/' . $matches[1]);
//                }
//            }
//        }
//    }

    /**
     * Bootrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function _bootstrapCli(): void
    {
        // Set logs to different files, so they don't have permission conflicts.
        Configure::write('Log.debug.file', 'cli-debug');
        Configure::write('Log.error.file', 'cli-error');

        // Include the CLI bootstrap overrides.
        if (file_exists($this->configDir . '/bootstrap_cli.php')) {
            require $this->configDir . '/bootstrap_cli.php';
        }

        // Attempt to load standard cli plugins
        foreach (['Bake', 'Migrations', 'Repl'] as $pluginName) {
            $this->addOptionalPlugin($pluginName);
        }
    }

    /**
     * Apply system configurations.
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
         * Error handler
         *
         * @todo ErrorHandler is deprecated in CakePHP ^4.4
         * The ErrorHandler and ConsoleErrorHandler classes are now deprecated.
         * They have been replaced by the new ExceptionTrap and ErrorTrap classes.
         * The trap classes provide a more extensible and consistent error & exception handling framework.
         * To upgrade to the new system you can replace the usage of ErrorHandler and ConsoleErrorHandler
         */
//        if ($isCli) {
//            (new \Cake\Error\ConsoleErrorHandler(Configure::read('Error')))->register();
//            //} elseif (class_exists('\Gourmet\Whoops\Error\WhoopsHandler')) {
//            // Out-of-the-box support for "Whoops for CakePHP3" by "gourmet"
//            // https://github.com/gourmet/whoops
//            //    (new \Gourmet\Whoops\Error\WhoopsHandler(Configure::read('Error')))->register();
//        } else {
//            (new ErrorHandler(Configure::read('Error')))->register();
//        }
        (new \Cake\Error\ErrorTrap(Configure::read('Error')))->register();
        (new \Cake\Error\ExceptionTrap(Configure::read('Error')))->register();

        /*
         * Set the full base URL.
         * This URL is used as the base of all absolute links.
         */
        $fullBaseUrl = Configure::read('App.fullBaseUrl');
        if (!$fullBaseUrl) {
            //@todo Trusted proxy
            /*
             * When using proxies or load balancers, SSL/TLS connections might
             * get terminated before reaching the server. If you trust the proxy,
             * you can enable `$trustProxy` to rely on the `X-Forwarded-Proto`
             * header to determine whether to generate URLs using `https`.
             *
             * See also https://book.cakephp.org/4/en/controllers/request-response.html#trusting-proxy-headers
             */
            $trustProxy = false;

            $s = null;
            if (env('HTTPS') || ($trustProxy && env('HTTP_X_FORWARDED_PROTO') === 'https')) {
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
        unset($fullBaseUrl);

        Cache::setConfig(Configure::consume('Cache'));
        ConnectionManager::setConfig(Configure::consume('Datasources'));
        TransportFactory::setConfig(Configure::consume('EmailTransport'));
        Mailer::setConfig(Configure::consume('Email'));
        Log::setConfig(Configure::consume('Log'));
        Security::setSalt(Configure::consume('Security.salt'));

        /**
         * Setup detectors for mobile and tablet.
         */
        ServerRequest::addDetector('mobile', function () {
            $detector = new \Detection\MobileDetect();
            return $detector->isMobile();
        });
        ServerRequest::addDetector('tablet', function () {
            $detector = new \Detection\MobileDetect();
            return $detector->isTablet();
        });

        /**
         * Register database types
         *
         * You can enable default locale format parsing by adding calls
         * to `useLocaleParser()`. This enables the automatic conversion of
         * locale specific date formats. For details see
         * @link https://book.cakephp.org/4/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
         */
        // \Cake\Database\TypeFactory::build('time')
        //    ->useLocaleParser();
        // \Cake\Database\TypeFactory::build('date')
        //    ->useLocaleParser();
        // \Cake\Database\TypeFactory::build('datetime')
        //    ->useLocaleParser();
        // \Cake\Database\TypeFactory::build('timestamp')
        //    ->useLocaleParser();
        // \Cake\Database\TypeFactory::build('datetimefractional')
        //    ->useLocaleParser();
        // \Cake\Database\TypeFactory::build('timestampfractional')
        //    ->useLocaleParser();
        // \Cake\Database\TypeFactory::build('datetimetimezone')
        //    ->useLocaleParser();
        // \Cake\Database\TypeFactory::build('timestamptimezone')
        //    ->useLocaleParser();

        // There is no time-specific type in Cake
        //\Cake\Database\TypeFactory::map('time', StringType::class);

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
    protected function _debugmode(bool $enabled): void
    {
        if ($enabled) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'on');

            Configure::write('Cache._cake_model_.duration', '+2 minutes');
            Configure::write('Cache._cake_core_.duration', '+2 minutes');
            Configure::write('Cache._cake_routes_.duration', '+2 seconds');

            Configure::write('Asset.timestamp', true);
            Configure::write('Asset.cacheTime', '+2 seconds');
        } else {
            error_reporting(0);
            ini_set('display_errors', 'off');
            // When debug = false the metadata cache should last
            // for a very long time, as we don't want
            // to refresh the cache while users are doing requests.
            //Configure::write('Cache._cake_model_.duration', '+1 years');
            //Configure::write('Cache._cake_core_.duration', '+1 years');
        }
    }

    /**
     * Check system requirements.
     *
     * @return void
     */
    protected function _checkRequirements(): void
    {
        $reqFilePath = $this->configDir . 'requirements.php';
        if (is_file($reqFilePath)) {
            require_once $reqFilePath;
        }

        if (version_compare(PHP_VERSION, '7.4.0') < 0) {
            trigger_error('Your PHP version must be equal or higher than 7.4.0 to use CakePHP.', E_USER_ERROR);
        }

        if (!extension_loaded('mbstring')) {
            trigger_error('You must enable the mbstring extension to use CakePHP.', E_USER_ERROR);
        }

        if (!extension_loaded('intl')) {
            trigger_error('You must enable the intl extension to use CakePHP.', E_USER_ERROR);
        }

        if (version_compare(INTL_ICU_VERSION, '50.1', '<')) {
            trigger_error('ICU >= 50.1 is needed to use CakePHP. Please update the `libicu` package of your system.' . PHP_EOL, E_USER_ERROR);
        }
    }
}
