<?php

namespace Banana;

use Cake\Core\Configure;

/**
 * Class Banana
 *
 * @package Banana
 * @todo Refactor as (service) container
 * @todo Caching service
 * @todo Log service
 */
class Banana
{
    /**
     * @var string Default mailer class
     */
    static public $mailerClass = 'Cake\Mailer\Mailer';

    /**
     * List of Banana instances. Singleton holder.
     */
    static protected $_instances = [];

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
     * Static access to the plugin handler
     */
    static public function plugin($pluginName)
    {
        return self::getInstance()->app()->plugins()->get($pluginName);
    }

    /**
     * Static access to the plugin info
     */
    static public function pluginInfo($pluginName)
    {
        return self::getInstance()->app()->getPluginInfo($pluginName);
    }


    /**
     * Singleton instance constructor
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->_app = $app;
    }

    public function plugins()
    {
        return $this->_app->plugins();
    }

    public function app()
    {
        return $this->_app;
    }
}
