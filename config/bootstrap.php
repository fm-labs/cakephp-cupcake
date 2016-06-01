<?php
use Backend\Lib\Backend;
use Banana\Core\Banana;
use Cake\Core\Configure;
use Cake\Core\Plugin;

if (!Configure::read('Banana')) {
    die("Banana Plugin not configured");
}

/**
 * Core Banana plugins (required)
 */
Plugin::load('Backend', ['bootstrap' => true, 'routes' => true]);
Plugin::load('User', ['bootstrap' => true, 'routes' => true]);
Plugin::load('Tree', ['bootstrap' => true, 'routes' => false]);
Plugin::load('Media', ['bootstrap' => true, 'routes' => true]);
Plugin::load('Settings', ['bootstrap' => true, 'routes' => true]);

/**
 * Theme plugins
 */
if (Configure::check('Banana.Frontend.theme')) {
    try {
        Plugin::load(Configure::read('Banana.Frontend.theme'), ['bootstrap' => true, 'routes' => true]);
    } catch (\Cake\Core\Exception\Exception $ex) {
        die ($ex->getMessage());
    }
}

/**
 * Backend hook
 */
Backend::hookPlugin('Banana');