<?php
namespace Banana\Plugin;

use Banana\Application;
use Cake\Core\Exception\Exception;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\Plugin;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;

abstract class BasePlugin implements PluginInterface
{
    use InstanceConfigTrait;

    protected $_defaultConfig = [];

    protected $_name;

    public function __construct(array $config)
    {
        if (!isset($this->_name)) {
            throw new Exception("Misconfigured plugin: Undefined name in " . get_class($this));
        }

        $this->config($config);
    }

    /**
     * {@inheritDoc}
     */
    public function bootstrap(Application $app)
    {
        $file = Plugin::configPath($this->_name) . 'bootstrap.php';
        if (file_exists($file)) {
            include $file;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function routes(RouteBuilder $routes)
    {
        return $routes;
    }

    /**
     * {@inheritDoc}
     */
    public function middleware(MiddlewareQueue $middleware)
    {
        return $middleware;
    }
}
