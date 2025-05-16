<?php
declare(strict_types=1);

namespace Cupcake;

use Cake\Cache\Cache;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

class CupcakePlugin extends BasePlugin
{
    //    /**
    //     * @var bool
    //     */
    //    public $routesEnabled = false;
    //
    //    /**
    //     * @var bool
    //     */
    //    public $bootstrapEnabled = true;

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        $app->addOptionalPlugin('Settings');

        // Set default DATA_DIR
        defined('DATA_DIR') || define('DATA_DIR', ROOT . DS . 'data' . DS);

        // Cache configuration
        if (!Cache::getConfig('cupcake')) {
            Cache::setConfig('cupcake', [
                'className' => 'File',
                'duration' => '+1 hours',
                'path' => CACHE,
                'prefix' => 'cupcake_core_',
            ]);
        }

        // DebugKit integration
        if (Plugin::isLoaded('DebugKit')) {
            $panels = Configure::read('DebugKit.panels', []);
            //print_r($panels);
            $panels['Cupcake.System'] = true;
            Configure::write('DebugKit.panels', $panels);
        }

        // Settings integration
        if (Plugin::isLoaded('Settings')) {
            Configure::load('Cupcake', 'settings');
        }

//        EventManager::instance()->on('Controller.beforeRender', function(EventInterface $event) {
//            if ($event->getSubject() instanceof \DebugKit\Controller\MailPreviewController) {
//                //debug("Controller.initialize: " . get_class($event->getSubject()));
//                /** @var \DebugKit\Controller\MailPreviewController $controller */
//                $controller = $event->getSubject();
//                $controller->viewBuilder()->setLayout("Cupcake.mail_preview");
//                $controller->viewBuilder()->enableAutoLayout(true);
//            }
//        });
    }

    public function routes(RouteBuilder $routes): void
    {
        // Set global default route class
        // @todo: Make this configurable
        $routes->setRouteClass(DashedRoute::class);

        // Default cupcake routes
        // @todo: Make this configurable
        $routes->scope('/', function (RouteBuilder $builder): void {
            $builder->connect('/', ['plugin' => 'cupcake', 'controller' => 'Pages', 'action' => 'display', 'home']);
            $builder->connect('/pages/*', ['plugin' => 'cupcake', 'controller' => 'Pages', 'action' => 'display']);
            $builder->fallbacks();
        });
    }
}
