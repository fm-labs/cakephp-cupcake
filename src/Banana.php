<?php

namespace Banana;

use Backend\Backend;
use Banana\Plugin\PluginInterface;
use Banana\Plugin\PluginManager;
use Cake\Core\Configure;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\BaseApplication;
use Settings\SettingsManager;

/**
 * Class Banana
 *
 * @package Banana
 * @todo Refactor as (service) container
 * @todo Caching service
 * @todo Log service
 */
class Banana implements EventDispatcherInterface
{
    use EventDispatcherTrait;

    /**
     * @var string Default mailer class
     */
    static public $mailerClass = 'Cake\Mailer\Mailer';

    /**
     * List of Banana instances. Singleton holder.
     */
    static protected $_instances = [];

    /**
     * Core plugins config
     * @var array
     */
    static protected $_corePlugins = [
        'Banana'    => ['bootstrap' => true, 'routes' => true, 'protected' => true],
        'Settings'  => ['bootstrap' => true, 'routes' => true, 'protected' => true],
        'Backend'   => ['bootstrap' => true, 'routes' => true, 'protected' => true],
        'User'      => ['bootstrap' => true, 'routes' => true, 'protected' => true]
    ];

    /**
     * @var BaseApplication
     */
    protected $_app;

    /**
     * @var PluginManager
     */
    protected $_pluginManager;

    /**
     * @var SettingsManager
     */
    protected $_settingsManager;

    /**
     * @var Backend
     */
    protected $_backend;

    /**
     * Banana-app wide common mailer instance
     *
     * @return \Cake\Mailer\Mailer
     */
    static public function getMailer()
    {
        return new self::$mailerClass();
    }

    /**
     * Singleton getter
     * @return Banana
     * @throws \Exception
     */
    static public function init(Application $app)
    {
        if (isset(self::$_instances[0])) {
            throw new \Exception('Banana::init: Already initialized');
        }

        return self::$_instances[0] = new self($app);
    }

    /**
     * Singleton getter
     * @return Banana
     * @throws \Exception
     */
    static public function getInstance()
    {
        if (!isset(self::$_instances[0])) {
            throw new \Exception('Banana::getInstance: Not initialized');
        }
        return self::$_instances[0];
    }

    /**
     * Singleton instance constructor
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->_app = $app;

        // bootstrap plugins
        foreach ($this->pluginManager()->enabled() as $pluginName) {
            $plugin = $this->pluginManager()->get($pluginName);
            if ($plugin instanceof PluginInterface) {
                $plugin->bootstrap($this->_app);
            }
        }
    }

    public function run()
    {
    }

    public function runBackend()
    {
        $this->eventManager()->on($this->backend());
        $this->dispatchEvent('Backend.startup', [], $this);
    }

    /**
     * Get / Set application instance
     * @param Application $app
     * @return Application
     */
    public function application(Application $app = null)
    {
        if ($app !== null) {
            $this->_app = $app;
        }
        return $this->_app;
    }

    /**
     * Get / Set plugin mananager instance
     * @param PluginManager $pluginManager
     * @return PluginManager
     */
    public function pluginManager(PluginManager $pluginManager = null)
    {
        return $this->_app->plugins();
    }

    /**
     * Get / Set settings mananager instance
     * @param SettingsManager $settingsManager
     * @return SettingsManager
     */
    public function settingsManager(SettingsManager $settingsManager = null)
    {
        return $this->_app->settings();
    }

    /**
     * Get Backend instance
     * @return Backend
     */
    public function backend()
    {
        return $this->_app->backend();
    }

    /**
     * Static direct accessor to plugin handler
     * @return object|null
     */
    static public function Plugin($pluginName)
    {
        return self::getInstance()->pluginManager()->get($pluginName);
    }

    /**
     * Static direct accessor to Application instance
     * @return Application
     */
    static public function App()
    {
        return self::getInstance()->application();
    }
}
