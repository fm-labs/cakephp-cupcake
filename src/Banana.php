<?php

namespace Banana;

use Banana\Plugin\PluginRegistry;
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
     * @deprecated
     */
    const VERSION = "0.4.0";

    /**
     * @var string Default mailer class
     */
    public static $mailerClass = 'Cake\Mailer\Mailer';

    /**
     * List of Banana instances. Singleton holder.
     */
    protected static $_instances = [];

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
     * Singleton getter
     * @return Banana
     * @throws \Exception
     */
    public static function init(Application $app)
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
    public static function getInstance()
    {
        if (!isset(self::$_instances[0])) {
            throw new \Exception('Banana::getInstance: Not initialized');
        }

        return self::$_instances[0];
    }

    /**
     * Static access to the plugin handler
     */
    public static function plugin($pluginName)
    {
        return self::getInstance()->app()->plugins()->get($pluginName);
    }

    /**
     * Static access to the plugin info
     */
    public static function pluginInfo($pluginName)
    {
        return self::getInstance()->app()->getPluginInfo($pluginName);
    }

    /**
     * Get Banana Cake version
     *
     * @return string
     */
    public static function version()
    {
        return self::VERSION;
    }

    /**
     * Singleton instance constructor
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->_app = $app;
    }

    /**
     * @return PluginRegistry
     */
    public function plugins()
    {
        return $this->_app->plugins();
    }

    /**
     * @return Application
     */
    public function app()
    {
        return $this->_app;
    }
}
