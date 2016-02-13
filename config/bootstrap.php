<?php
use Backend\Lib\Backend;
use Banana\Core\Banana;
use Cake\Core\Configure;
use Cake\Core\Plugin;

/**
 * Core Configs
 */
Configure::load('banana');
Configure::load('backend');
Configure::load('shop');
Configure::load('media');

if (!Configure::read('Banana')) {
    die("Banana Plugin not configured");
}


//Banana::bootstrap();
//Banana::bootstrapConfigs();
//Banana::bootstrapPlugins();

/**
 * Core Banana plugins (required)
 */
Plugin::load('Backend', ['bootstrap' => true, 'routes' => true]);
Backend::hookPlugin('Banana');

Plugin::load('User', ['bootstrap' => true, 'routes' => true]);
Plugin::load('Tree', ['bootstrap' => true, 'routes' => false]);
Plugin::load('Media', ['bootstrap' => true, 'routes' => true]);
Plugin::load('Settings', ['bootstrap' => true, 'routes' => true]);

/**
 * Theme plugins
 */
if (Configure::check('Banana.Frontend.theme')) {
    Plugin::load(Configure::read('Banana.Frontend.theme'), ['bootstrap' => true, 'routes' => true]);
}


/**
 *
 */

//Configure::load('global', 'settings');
//Configure::load('site1', 'settings');