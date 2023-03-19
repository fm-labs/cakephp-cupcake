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
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Utility\Inflector;
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
         * - include user's bootstrap file
         * - bootstrap cakephp
         * - load plugins
         * - initialize cupcake
         */

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
            $bootstrapper = \Cupcake\Bootstrapper::init($this->configDir);
            $bootstrapper->run();
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            throw $ex;
        }

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
