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
     * Singleton initializer
     * @param Application $app
     * @param PluginManager $plugins
     * @param SettingsManager $settings
     */
    static public function init(Application $app, PluginManager $plugins = null, SettingsManager $settings = null)
    {
        if (isset(self::$_instances[0])) {
            throw new \RuntimeException("Banana already initialized");
        }

        self::$_instances[0] = new self($app, $plugins, $settings);
    }

    /**
     * Singleton getter
     * @return Banana
     */
    static public function getInstance()
    {
        if (!isset(self::$_instances[0])) {
            throw new \RuntimeException("Banana not initialized");
        }
        return self::$_instances[0];
    }

    /**
     * Singleton instance constructor
     * @param Application $app
     * @param PluginManager $plugins
     * @param SettingsManager $settings
     */
    public function __construct(Application $app, PluginManager $plugins = null, SettingsManager $settings = null)
    {
        // set application context
        $this->application($app);

        // connect plugin- and settings-manager
        $this->eventManager()->on($this->pluginManager($plugins));
        $this->eventManager()->on($this->settingsManager($settings));

        // connect backend
        $this->eventManager()->on($this->backend());

        // broadcast to all services that we are ready :)
        $this->dispatchEvent('Banana.init', [], $this);
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
