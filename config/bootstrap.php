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
//Plugin::load('Media', ['bootstrap' => true, 'routes' => true]);
Plugin::load('Settings', ['bootstrap' => true, 'routes' => true]);


/**
 * User plugins
 * Plugins with an plugin config in config/plugins will be loaded now
 */
BananaPlugin::loadAll();

/**
 * Themes
 */
//BananaTheme::loadAll();

/**
 * Banana init
 */
//Banana\Lib\Banana::init();

/**
 * Backend hook
 */
Backend\Lib\Backend::hookPlugin('Banana');
