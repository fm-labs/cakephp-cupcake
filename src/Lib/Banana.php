<?php

namespace Banana\Lib;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventManager;
use Cake\Http\BaseApplication;
use Settings\SettingsManager;

/**
 * Class Banana
 *
 * @package Banana\Lib
 */
class Banana implements EventDispatcherInterface
{
    use SingletonTrait {
        getInstance as _getInstance;
    }

    /**
     * @var string Default mailer class
     */
    static $mailerClass = 'Cake\Mailer\Mailer';

    /**
     * @var SettingsManager
     */
    protected $_settingsManager;

    /**
     * @var EventManager
     */
    protected $_eventManager;

    /**
     * @var BaseApplication
     */
    protected $_app;

    /**
     * @return Banana
     */
    static public function getInstance()
    {
        return self::_getInstance();
    }

    /**
     * Banana-app wide common mailer instance
     *
     * @return \Cake\Mailer\Mailer
     */
    public static function getMailer()
    {
        return new self::$mailerClass();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->settingsManager();
        $this->eventManager();
    }

    /**
     * Get / Set application instance
     */
    public function application(BaseApplication $app = null)
    {
        if ($app !== null) {
            $this->_app = $app;
        }
        return $this->_app;
    }

    /**
     * Get / Set settings mananager instance
     */
    public function settingsManager(SettingsManager $settingsManager = null)
    {
        if ($settingsManager !== null) {
            $this->_settingsManager = $settingsManager;
        } elseif (!$this->_settingsManager) {
            $this->_settingsManager = new SettingsManager(BC_SITE_ID);
        }
        return $this->_settingsManager;
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
    public function eventManager(EventManager $eventManager = null)
    {
        if ($eventManager !== null) {
            $this->_eventManager = $eventManager;
        } elseif (!$this->_eventManager) {
            $this->_eventManager = EventManager::instance();
        }
        return $this->_eventManager;
    }
    
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
    public function dispatchEvent($name, $data = null, $subject = null)
    {
        if ($subject === null) {
            $subject = $this;
        }
        return $this->eventManager()->dispatch(new Event($name, $subject, $data));
    }


    public function getSettings()
    {

    }

}
