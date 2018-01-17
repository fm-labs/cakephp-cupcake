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
 */
class Banana implements EventDispatcherInterface
{
    use EventDispatcherTrait;

    /**
     * @var string Default mailer class
     * @todo Move
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
     * @return void
     */
    static public function init(Application $app)
    {
        if (isset(self::$_instances[0])) {
            throw new \RuntimeException("Banana already initialized");
        }

        self::$_instances[0] = new self($app);
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
     */
    public function __construct(Application $app)
    {
        $this->_app = $app;

        $this->_pluginManager = new PluginManager();
        $this->eventManager()->on($this->_pluginManager);

        $this->_settingsManager = new SettingsManager();
        $this->eventManager()->on($this->_settingsManager);

        $this->_backend = new Backend();
        $this->eventManager()->on($this->_backend);

        $this->dispatchEvent('Banana.init');
    }

    /**
     * Get / Set application instance
     * @return Application
     */
    public function application(/* Application $app = null */)
    {
//        if ($app !== null) {
//            $this->_app = $app;
//        }
        return $this->_app;
    }

    /**
     * Get / Set plugin mananager instance
     * @return PluginManager
     */
    public function pluginManager(/*SettingsManager $pluginManager = null*/)
    {
//        if ($pluginManager !== null) {
//            $this->_pluginManager = $pluginManager;
//        } elseif (!$this->_pluginManager) {
//            $this->_pluginManager = new SettingsManager();
//        }
        return $this->_pluginManager;
    }
    
    /**
     * Get / Set settings mananager instance
     * @return SettingsManager
     */
    public function settingsManager(/*SettingsManager $settingsManager = null*/)
    {
//        if ($settingsManager !== null) {
//            $this->_settingsManager = $settingsManager;
//        } elseif (!$this->_settingsManager) {
//            $this->_settingsManager = new SettingsManager();
//        }
        return $this->_settingsManager;
    }

    /**
     * Get Backend instance
     * @return Backend
     */
    public function backend()
    {
        return $this->_backend;
    }

    /**
     * Returns the Cake\Event\EventManager manager instance for this object.
     *
     * You can use this instance to register any new listeners or callbacks to the
     * object events, or create your own events and trigger them at will.
     *
     * @param \Cake\Event\EventManager|null $eventManager the eventManager to set
     * @return \Cake\Event\EventManager
     */
//    public function eventManager(EventManager $eventManager = null)
//    {
//        if ($eventManager !== null) {
//            $this->_eventManager = $eventManager;
//        } elseif (!$this->_eventManager) {
//            $this->_eventManager = EventManager::instance();
//        }
//        return $this->_eventManager;
//    }
    
    /**
     * Wrapper for creating and dispatching events.
     *
     * Returns a dispatched event.
     *
     * @param string $name Name of the event.
     * @param array|null $data Any value you wish to be transported with this event to
     * it can be read by listeners.
     * @param object|null $subject The object that this event applies to
     * ($this by default).
     *
     * @return \Cake\Event\Event
     */
//    public function dispatchEvent($name, $data = null, $subject = null)
//    {
//        if ($subject === null) {
//            $subject = $this;
//        }
//        return $this->eventManager()->dispatch(new Event($name, $subject, $data));
//    }
}
