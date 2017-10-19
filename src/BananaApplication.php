<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Banana;

use Banana\Lib\Banana;
use Banana\Middleware\PluginMiddleware;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class BananaApplication extends BaseApplication
{
    static protected $_sites = [];

    /**
     * @var string
     */
    protected $sitesConfigDir;

    /**
     * @var string Currently selected siteId
     */
    protected $siteId;

    static public function configureSite($siteId, array $config = [])
    {
        $config = array_merge(['hosts' => null], $config);
        static::$_sites[$siteId] = $config;
    }

    /**
     * Constructor
     *
     * @param string $configDir The directory the bootstrap configuration is held in.
     */
    public function __construct($configDir, $siteId = null)
    {
        if (file_exists($configDir . '/env.php')) {
            require_once $configDir . '/env.php';
        }

        // determine site id from environment
        $siteId = ($siteId === null && defined('BC_SITE_ID')) ? constant('BC_SITE_ID') : $siteId;
        $siteId = ($siteId === null && defined('ENV')) ? constant('ENV') : $siteId; //@deprecated @legacy

        // determine site id from HTTP request
        if ($siteId === null) {
            $httpHost = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : null;
            if (getenv('OVERRIDE_HTTP_HOST')) {
                $httpHost = getenv('OVERRIDE_HTTP_HOST');
            }

            foreach (static::$_sites as $_siteId => $site) {
                $hosts = (array) static::$_sites[$_siteId]['hosts'];
                if (in_array($httpHost, $hosts)) {
                    $siteId = $_siteId;
                    break;
                }
            }
        }

        // fallback to default site
        $this->sitesConfigDir = $configDir;
        $this->siteId = $siteId = ($siteId) ?: 'default';

        parent::__construct($configDir . DS . 'sites' . DS . $siteId);
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
        // prepare the environment
        if (file_exists($this->configDir . '/env.php'))
            require_once $this->configDir . '/env.php';

        if (file_exists($this->configDir . '/paths.php'))
            require_once $this->configDir . '/paths.php';

        // bootstrap cake
        if (!defined('CORE_PATH')) {
            throw new \Exception('CORE_PATH is not defined.');
        }
        require CORE_PATH . 'config' . DS . 'bootstrap.php';

        // load app configs
        try {
            Configure::config('default', new PhpConfig());

            // app config
            Configure::load('app', 'default', false);

            // site config
            Configure::load('site');

            // beta config overrides
            if (defined('ENV_BETA')) {
                Configure::load('beta');
                Configure::write('App.beta', ENV_BETA);
            }

            // local config overrides
            try { Configure::load('local/app'); } catch(\Exception $ex) {}

            // local plugin config overrides
            try { Configure::load('local/cake-plugins'); } catch(\Exception $ex) {}

        } catch (\Exception $e) {
            die($e->getMessage() . "\n");
        }


        // bootstrap app
        if (file_exists($this->configDir . '/bootstrap.php'))
            require_once $this->configDir . '/bootstrap.php';

        // local bootstrap override (only used for dev)
        if (file_exists($this->sitesConfigDir . ".local.php")) {
            require $this->sitesConfigDir . ".local.php";
        }

        // bootstrap banana
        Plugin::load('Banana', ['bootstrap' => true, 'routes' => true]);
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
            ->add(new PluginMiddleware())

            // Apply routing
            ->add(new RoutingMiddleware());

        return $middleware;
    }
}
