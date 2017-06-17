<?php

namespace Banana\Lib;

use Banana\Plugin\PluginLoader;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Utility\Security;

/**
 * Class Banana
 *
 * @package Banana\Lib
 */
class Banana
{
    /**
     * @var string Default mailer class
     */
    static $mailerClass = 'Cake\Mailer\Mailer';

    /**
     * @var string Default theme config key
     */
    static $themeKey = 'Site.theme';

    /**
     * Basic CakePHP bootstrap config
     *
     * @return void
     */
    public static function configure()
    {

        // Set the full base URL.
        // This URL is used as the base of all absolute links.
        if (!Configure::read('App.fullBaseUrl')) {
            $s = null;
            if (env('HTTPS')) {
                $s = 's';
            }

            $httpHost = env('HTTP_HOST');
            if (isset($httpHost)) {
                Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
            }
            unset($httpHost, $s);
        }

        // Only try to load DebugKit in development mode
        // Debug Kit should not be installed on a production system
        if (Configure::read('debug')) {
            Plugin::load('DebugKit', ['bootstrap' => true]);
        }

        // When debug = false the metadata cache should last
        // for a very very long time, as we don't want
        // to refresh the cache while users are doing requests.
        if (!Configure::read('debug')) {
            Configure::write('Cache._cake_model_.duration', '+1 years');
            Configure::write('Cache._cake_core_.duration', '+1 years');
        }

        Cache::config(Configure::consume('Cache'));
        ConnectionManager::config(Configure::consume('Datasources'));
        Log::config(Configure::consume('Log'));
        Security::salt(Configure::consume('Security.salt'));
        Email::configTransport(Configure::consume('EmailTransport'));
        Email::config(Configure::consume('Email'));
    }

    /**
     * Convenience method to load all banana-related stuff.
     * Best pratice is to use this method right after loading the Banana plugin in your app's bootstrap process
     *
     * @return void
     */
    public static function load()
    {
        static::configure();
        static::loadPlugins();
        static::loadThemes();
        static::loadSettings();
    }

    /**
     * Load registered Banana plugins
     *
     * @return void
     * @throws \Exception If plugin loading failed
     */
    public static function loadPlugins()
    {
        // core plugins
        PluginLoader::load('Backend', ['bootstrap' => true, 'routes' => true]); //@todo remove hard plugin dependency
        PluginLoader::load('User', ['bootstrap' => true, 'routes' => true]); //@todo remove hard plugin dependency
        PluginLoader::load('Tree', ['bootstrap' => true, 'routes' => false]); //@todo remove hard plugin dependency

        // registered plugins
        PluginLoader::loadAll();
    }

    /**
     * Load registered Banana themes
     *
     * @return void
     * @throws \Exception If plugin loading failed
     */
    public static function loadThemes()
    {
        if (Configure::check('Site.theme')) {
            PluginLoader::load(Configure::read(static::$themeKey), ['bootstrap' => true, 'routes' => true]);
        }
    }

    /**
     * Load settings for current site
     * @return void
     * @throws \Exception If settings loading failed
     */
    public static function loadSettings()
    {
        if (!Plugin::loaded('Settings')) {
            debug("Settings plugin not loaded");
            // continue with default (manual) configuration
            return;
        }

        try {
            $site = (defined('ENV')) ? constant('ENV') : 'default';
            Configure::load($site, 'settings');
        } catch(\Exception $ex) {
            // continue with default configuration
            //debug($ex->getMessage());
        }
    }

    /**
     * Run all plugins
     */
    public static function run()
    {
        PluginLoader::runAll();
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
}
