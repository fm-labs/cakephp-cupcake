<?php

namespace Cupcake;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\FactoryLocator;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Cupcake\Configure\Engine\LocalPhpConfig;

class Bootstrapper
{
    /**
     * @var array
     */
    protected static array $instances = [];

    /**
     * @var bool Flag indicating if Bootstrapper has run
     */
    protected static bool $isBootstrapped = false;

    /**
     * @var string Path to application config dir
     */
    protected string $configDir;


    protected array $enabled = [
        'requirements' => true,
        'debug' => true,
        'cli' => true,
        'datasource' => true,
        'cache' => true,
        'error' => true,
        'log' => true,
        'security' => true,
        'mailer' => true,
        'mailer_transports' => true,
    ];

    public static function getInstance()
    {
        if (!isset(self::$instances[0])) {
            throw new \RuntimeException("Bootstrapper not initialized yet");
        }
        return self::$instances[0];
    }

    public static function init(string $configDir)
    {
        if (count(static::$instances) > 0) {
            throw new \RuntimeException("Bootstrapper already initialized");
        }
        $instance = new self($configDir);
        return static::$instances[] = $instance;
    }

    public static function reset()
    {
        foreach(ConnectionManager::configured() as $name) {
            ConnectionManager::drop($name);
        }
        foreach(TransportFactory::configured() as $name) {
            TransportFactory::drop($name);
        }
        foreach(Mailer::configured() as $name) {
            Mailer::drop($name);
        }
        foreach(Log::configured() as $name) {
            Log::drop($name);
        }
        Cache::clearAll();
        foreach(Cache::configured() as $name) {
            Cache::drop($name);
        }
        Security::setSalt('');
        Configure::clear();
    }

    protected function __construct(string $configDir)
    {
        $configDir = rtrim($configDir, DS) . DS;
        $this->configDir = $configDir;
    }

    /**
     * Skip named bootstrap step.
     *
     * @param string $name
     * @param bool $mode
     * @return $this
     */
    public function enable(string $name, bool $mode = true): static
    {
        $this->enabled[$name] = $mode;
        return $this;
    }

    /**
     * Check if named bootstrap step is skipped.
     *
     * @param string $name
     * @return bool
     */
    public function isEnabled(string $name): bool
    {
        return $this->enabled[$name] ?? false;
    }

    public function run(): void
    {
        if (static::$isBootstrapped) {
            debug("Already bootstrapped");
            return;
        }

        /**
         * Check system requirements
         */
        if ($this->isEnabled('requirements')) {
            $this->_checkRequirements();
        }

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
        $configEngine = new LocalPhpConfig($this->configDir);
        Configure::config('default', $configEngine);
        Configure::load('app', 'default', false);
        if (file_exists(CONFIG . 'app_local.php')) {
            Configure::load('app_local', 'default');
        }
        if (file_exists(CONFIG . 'local' . DS . 'app.php')) {
            Configure::load('local/app', 'default');
        }

        /**
         * Debug mode.
         * Must be run before boostrap, to be able to patch configuration values before they get consumed.
         */
        if ($this->isEnabled('debug')) {
            $this->_debugMode(Configure::read('debug'));
        }

        /**
         * Cli
         */
        if (php_sapi_name() === 'cli' && $this->isEnabled('cli')) {
            $this->_bootstrapCli();
        } else {
            //@link https://github.com/cakephp/app/blob/4.x/src/Application.php
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        /**
         * Common bootstrapping tasks
         */
        $this->_bootstrap();

        static::$isBootstrapped = true;
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

//        // Attempt to load standard cli plugins
//        foreach (['Bake', 'Migrations', 'Repl'] as $pluginName) {
//            $this->addOptionalPlugin($pluginName);
//        }
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
        if ($this->isEnabled('error')) {
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
        }

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


        if ($this->isEnabled('cache')) {
            Cache::setConfig(Configure::consume('Cache'));
        }
        if ($this->isEnabled('datasource')) {
            ConnectionManager::setConfig(Configure::consume('Datasources'));
        }
        if ($this->isEnabled('mailer')) {
            TransportFactory::setConfig(Configure::consume('EmailTransport'));
        }
        if ($this->isEnabled('mailer_transport')) {
            Mailer::setConfig(Configure::consume('Email'));
        }
        if ($this->isEnabled('log')) {
            Log::setConfig(Configure::consume('Log'));
        }
        if ($this->isEnabled('security')) {
            Security::setSalt(Configure::consume('Security.salt'));
        }
        
        /**
         * Setup detectors for mobile and tablet.
         */
        if ($this->isEnabled('detectors')) {
            ServerRequest::addDetector('mobile', function () {
                $detector = new \Detection\MobileDetect();
                return $detector->isMobile();
            });
            ServerRequest::addDetector('tablet', function () {
                $detector = new \Detection\MobileDetect();
                return $detector->isTablet();
            });
        }
        
        
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