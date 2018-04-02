<?php
namespace Banana;

use Backend\Routing\Middleware\BackendMiddleware;
use Banana\Banana;
use Banana\Middleware\BananaMiddleware;
use Banana\Plugin\PluginManager;
use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\Configure;
use Cake\Core\Configure\ConfigEngineInterface;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Database\Type;
use Cake\Error\ErrorHandler;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\BaseApplication;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Network\Request;
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
class Application extends BaseApplication
{
    /**
     * @param string $configDir
     */
    public function __construct($configDir)
    {
        //@TODO Remove . Dev only
        //ini_set('display_errors', 1);
        //error_reporting(E_ALL);

        parent::__construct($configDir);
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
        try {
            Configure::config('default', $this->getDefaultConfigEngine());
            $this->bootstrapConfig();
        //} catch (\Cake\Core\Exception\Exception $ex) {
        //    die ($ex->getMessage());
        } catch (\Exception $ex) {
            die ($ex->getMessage());
        }

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
        ini_set('intl.default_locale', 'de'); //@TODO Make default locale configurable

        /**
         * Register application error and exception handlers.
         * @todo Inject cli configurations from banana
         */
//        $isCli = php_sapi_name() === 'cli';
//        if ($isCli) {
//            (new ConsoleErrorHandler(Configure::read('Error')))->register();
//
//            // Include the CLI bootstrap overrides.
//            require $this->configDir . '/bootstrap_cli.php';
//            //} elseif (class_exists('\Gourmet\Whoops\Error\WhoopsHandler')) {
//            // Out-of-the-box support for "Whoops for CakePHP3" by "gourmet"
//            // https://github.com/gourmet/whoops
//            //    (new \Gourmet\Whoops\Error\WhoopsHandler(Configure::read('Error')))->register();
//        } else {
            (new ErrorHandler(Configure::read('Error')))->register();
//        }
        
        /**
         * Bootstrap site
         */
        require_once $this->configDir . '/bootstrap.php';

        /**
         * Setup detectors for mobile and tablet.
         * @todo Remove mobile request detectors from banana. Move to site's bootstrap
         */
        Request::addDetector('mobile', function ($request) {
            $detector = new \Detection\MobileDetect();
            return $detector->isMobile();
        });
        Request::addDetector('tablet', function ($request) {
            $detector = new \Detection\MobileDetect();
            return $detector->isTablet();
        });

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

        // load core plugins with routes enabled
        Plugin::load('Banana', ['bootstrap' => true, 'routes' => true]);
        //$B = Banana::getInstance();


        $B = Banana::init($this);
        $B->bootstrap();

        /**
         * At this point:
         * The banana core plugins have been LOADED and ACTIVATED
         * All activated banana plugins have been LOADED
         */
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
    protected function bootstrapConfig()
    {
        // app config
        Configure::load('app', 'default', false);
        Configure::load('site');
        Configure::load('plugins');

        // beta config overrides
        // @TODO Replace with environment configs
        if (defined('ENV_BETA')) {
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
    protected function setDebugMode($enabled = true)
    {
        if ($enabled) {
            // disable Mail panel by default, as it doesn't play nice with Mailman plugin
            // @TODO Play nice with DebugKit
            if (!Configure::check('DebugKit.panels')) {
                Configure::write('DebugKit.panels', ['DebugKit.Mail' => false]);
            }

            try {
                Plugin::load('DebugKit', ['bootstrap' => true, 'routes' => true]);
            } catch (\Exception $ex) {
                //debug("DebugKit: " . $ex->getMessage());
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
            ->add(new BananaMiddleware())
            ->add(new BackendMiddleware())

            // Apply routing
            ->add(new RoutingMiddleware());


        return $middleware;
    }
}
