<?php
declare(strict_types=1);

namespace Cupcake;

use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\Core\PluginCollection;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManagerInterface;
use Cake\Http\BaseApplication;
use Cake\Http\ControllerFactoryInterface;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\RouteBuilder;
use Cupcake\Middleware\RoutingErrorMiddleware;
use Exception;

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
     * @param string $configDir Path to config directory
     */
    public function __construct(
        string $configDir,
        ?EventManagerInterface $eventManager = null,
        ?ControllerFactoryInterface $controllerFactory = null,
    ) {
        parent::__construct($configDir, $eventManager, $controllerFactory);
    }

    /**
     * Load all the application configuration and bootstrap logic.
     * - include bootstrap file
     * - bootstrap cakephp
     * - load plugins
     * - initialize cupcake
     *
     * Override this method to add additional bootstrap logic for your application.
     *
     * @return void
     * @throws \Exception
     */
    public function bootstrap(): void
    {
        /**
         * Include app's bootstrap file
         */
        if (file_exists($this->configDir . 'bootstrap.php')) {
            //require_once $this->configDir . 'bootstrap.php';
            parent::bootstrap();
        }

        /**
         * Run common cake application bootstrap tasks
         * - setup paths
         * - bootstrap cake core
         * - setup default config engine
         * - load app config
         * - load plugins config
         * - load local configurations
         * - setup full base url in configuration
         * - configure: timezone, encoding, locale, error handler
         * - configure: request detectors, database types, debugmode
         * - consume configurations: ConnectionManager, Cache, Email, Log, Security
         */
        try {
            $bootstrapper = Bootstrapper::init($this->configDir);
            $bootstrapper->run();
        } catch (Exception $ex) {
            echo $ex->getMessage();
            throw $ex;
        }

        // Load core plugins and user plugins
        //        if (file_exists(CONFIG . 'plugins.php')) {
        //            Configure::load('plugins');
        //        }
        //        $this->addPlugin('Cupcake');
        //        $this->addPlugin((array)Configure::read('Plugin')/*, ['bootstrap' => true, 'routes' => true]*/);

        /**
         * CakePHP DebugKit support
         */
        if (Configure::read('DebugKit.enabled')) {
            $this->addOptionalPlugin('DebugKit');
        }

        /**
         * CLI
         * Register common cli plugins.
         * These optional plugins are automatically available if fm-labs/cakephp-devtools package is installed.
         */
        if (PHP_SAPI == 'cli') {
            $this->addOptionalPlugin('Bake');
            $this->addOptionalPlugin('Migrations');
            $this->addOptionalPlugin('Repl');
        }

        /*
         * Add cupcake templates path as fallback template search path
         */
        $templatePaths = Configure::read('App.paths.templates', []);
        $templatePaths[] = Plugin::templatePath('Cupcake');
        Configure::write('App.paths.templates', $templatePaths);

        /*
         * Init Cupcake
         */
        Cupcake::setApplication($this);
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
            try {
                $plugin->bootstrap($this);
            } catch (Exception $ex) {
                debug("Bootstrapping plugin {$plugin->getName()} failed: " . $ex->getMessage());
                //throw new Exception("Bootstrapping plugin {$plugin->getName()} failed: " . $ex->getMessage());
            }
        }
    }

    /**
     * Only try to load routes from config file, if the file is present.
     * The CakePHP Application enforces the presence of a routes.php file.
     * The Cupcake Application ignores the absence gracefully.
     *
     * @param \Cake\Routing\RouteBuilder $routes
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
     * @inheritDoc
     */
    public function addPlugin($name, array $config = []): Application
    {
        if (is_array($name)) {
            foreach ($name as $_name => $_config) {
                if (is_numeric($_name)) {
                    $_name = $_config;
                    $_config = $config;
                }

                if (is_bool($_config)) {
                    // skip disabled plugins
                    if ($_config === false) {
                        continue;
                    }
                    $_config = [];
                }

                $this->addPlugin($_name, $_config);
            }

            return $this;
        }

        // @deprecated
        $bootstrap = $config['bootstrap'] ?? true;
        if ($bootstrap === false) {
            //debug("Skipping disabled plugin: $name");
            return $this;
        }

        if (Plugin::isLoaded($name)) {
            return $this;
        }

        return parent::addPlugin($name, $config);
    }

    /**
     * @inheritDoc
     */
    public function addOptionalPlugin($name, array $config = []): Application
    {
        // check if explicitly disabled
        if (Configure::read('Plugin.' . $name) === false) {
            //debug("plugin $name is explicitly disabled");
            return $this;
        }

        try {
            $this->addPlugin($name, $config);
        } catch (MissingPluginException) {
            // ignore missing plugin
        }

        return $this;
    }

    /**
     * Get plugin info - DEPRECATED
     *
     * @param string $pluginName Plugin name
     * @return array
     * @deprecated Use PluginManager::getPluginInfo() instead.
     */
    public function getPluginInfo(string $pluginName): array
    {
        deprecationWarning('4.0.1', 'Application::getPluginInfo() is deprecated. Use PluginManager::getPluginInfo() instead.');

        return PluginManager::getPluginInfo($pluginName);
    }

    /**
     * Get plugin registry instance - DEPRECATED
     *
     * @return \Cake\Core\PluginCollection
     * @deprecated Use getPlugins() instead.
     */
    public function plugins(): PluginCollection
    {
        deprecationWarning('4.0.1', 'Application::plugins() is deprecated. Use getPlugins() instead.');

        return $this->getPlugins();
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
//        $routingCacheConfig = (bool)Configure::read('debug') || (bool)Configure::read('Routing.disabled') === true
//            ? null : Configure::read('Routing.cacheConfigName', '_cake_routes_');
//        $routingCacheConfig = '_cake_routes_';
        $routingCacheConfig = null;

        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Handle routing errors
            // Logs MissingRouteExceptions to separate file to not pollute the error log file
            ->add(new RoutingErrorMiddleware())

            // Add routing middleware.
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

//    /**
//     * Autoload plugin configs from standard directories
//     *
//     * @param string $plugin Plugin name
//     * @throws \Exception
//     * @deprecated Use LocalPhpConfig instead.
//     */
//    public function loadPluginConfig(string $plugin): void
//    {
//        deprecationWarning('Application::loadPluginConfig() is deprecated. Do not use.');
//
//        $file = Inflector::underscore($plugin);
//
//        // first try to load the default plugin configuration from the plugin
//        $filePath = Plugin::configPath($plugin) . DS . $file . '.php';
//        if (file_exists($filePath)) {
//            if (!Configure::load($plugin . '.' . $file, 'default', true)) {
//                throw new RuntimeException('Failed to load config file ' . $file);
//            }
//        }
//
//        foreach (['plugin', 'local/plugin'] as $dir) {
//            $filePath = $this->configDir . DS . $dir . DS . $file . '.php';
//            if (file_exists($filePath)) {
//                if (!Configure::load($dir . '/' . $file, 'default', true)) {
//                    throw new RuntimeException('Failed to load config file ' . $file);
//                }
//            }
//        }
//
//        if (Configure::isConfigured('settings')) {
//            Configure::load($plugin, 'settings');
//        }
//    }

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
}
