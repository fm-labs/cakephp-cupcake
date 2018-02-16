<?php

namespace Banana;

use Backend\Backend;
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
    static protected $_corePlugins = ['Banana', 'Settings', 'Backend', 'User'];

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
     */
    static public function getInstance()
    {
        if (!isset(self::$_instances[0])) {
            self::$_instances[0] = new self();
        }
        return self::$_instances[0];
    }

    /**
     * Singleton instance constructor
     */
    public function __construct()
    {
    }

    /**
     * Banana bootstrap process
     * @param Application $app
     */
    public function bootstrap(Application $app)
    {
        // set application context
        $this->application($app);

        // connect plugin manager
        //$this->extend('pluginManager', '\Banana\Plugin\PluginManager');
        $this->pluginManager();
        foreach (self::$_corePlugins as $pluginName) {
            $this->pluginManager()->load($pluginName, ['bootstrap' => true, 'routes' => true, 'protected' => true]);
        }
        $this->pluginManager()->bootstrap();

        // connect settings manager
        $this->settingsManager();
    }

    public function run()
    {
        $this->eventManager()->on($this->pluginManager());

        // connect backend
        //$this->backend();
        $this->eventManager()->on($this->backend());

        // broadcast to all services that we are ready :)
        $this->dispatchEvent('Banana.startup', [], $this);
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
        if ($pluginManager !== null) {
            $this->_pluginManager = $pluginManager;
        } elseif (!$this->_pluginManager) {
            $this->_pluginManager = new PluginManager();
        }
        return $this->_pluginManager;
    }

    /**
     * Get / Set settings mananager instance
     * @param SettingsManager $settingsManager
     * @return SettingsManager
     */
    public function settingsManager(SettingsManager $settingsManager = null)
    {
        if ($settingsManager !== null) {
            $this->_settingsManager = $settingsManager;
        } elseif (!$this->_settingsManager) {
            $this->_settingsManager = new SettingsManager();
        }
        return $this->_settingsManager;
    }

    /**
     * Get Backend instance
     * @return Backend
     */
    public function backend()
    {
        if (!$this->_backend) {
            $this->_backend = new Backend();
        }
        return $this->_backend;
    }
}
