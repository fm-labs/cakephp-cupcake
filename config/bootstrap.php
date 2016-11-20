<?php
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
 * Banana init
 */
//Banana\Lib\Banana::init();

/**
 * Backend hook
 */
Backend\Lib\Backend::hookPlugin('Banana');
