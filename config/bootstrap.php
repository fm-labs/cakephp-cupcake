<?php
use Banana\Lib\BananaPlugin;
use Cake\Core\Configure;
use Cake\Core\Plugin;

/**
 * Core Content plugins (required)
 */
Plugin::load('Backend', ['bootstrap' => true, 'routes' => true]);
Plugin::load('User', ['bootstrap' => true, 'routes' => true]);
Plugin::load('Tree', ['bootstrap' => true, 'routes' => false]);
Plugin::load('Media', ['bootstrap' => true, 'routes' => true]);
Plugin::load('Settings', ['bootstrap' => true, 'routes' => true]);


/**
 * User plugins
 */

/**
 * Themes
 */


/*
Plugin::load('Content', ['bootstrap' => true, 'routes' => false]);
Plugin::load('Shop', ['bootstrap' => true, 'routes' => true]);
Plugin::load('Newsletter', ['bootstrap' => true, 'routes' => true]);
Plugin::load('Mailman', ['bootstrap' => true, 'routes' => true]);
Plugin::load('Sitemap', ['bootstrap' => true, 'routes' => false]);
Plugin::load('Seo', ['bootstrap' => true, 'routes' => true]);

//Plugin::load('ThemeLederleitner', ['bootstrap' => true, 'routes' => true]);
Plugin::load('ThemeStone', ['bootstrap' => false, 'routes' => false]);
Plugin::load('ThemeCarpeNoctem', ['bootstrap' => false, 'routes' => false]);


try {

    Configure::config('settings', new \Settings\Configure\Engine\SettingsConfig());
    Configure::load('global', 'settings');

} catch (\Exception $ex) {
    die($ex->getMessage() . "\n");
}
*/

/**
 * Banana init
 */
//Banana\Lib\Banana::init();

/**
 * Backend hook
 */
Backend\Lib\Backend::hookPlugin('Banana');
