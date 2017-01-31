<?php
use Banana\Lib\BananaPlugin;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Database\Type;

/**
 * Core Banana plugins (required)
 */
Plugin::load('Backend', ['bootstrap' => true, 'routes' => true]);
Plugin::load('User', ['bootstrap' => true, 'routes' => true]);
Plugin::load('Tree', ['bootstrap' => true, 'routes' => false]);
Plugin::load('Settings', ['bootstrap' => true, 'routes' => true]);


//Type::map('json', 'Banana\Database\Type\JsonType');
Type::map('serialize', 'Banana\Database\Type\SerializeType');

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
