<?php
use Cake\Cache\Cache;

/**
 * Cache config
 */
if (!Cache::getConfig('banana')) {
    Cache::setConfig('banana', [
        'className' => 'File',
        'duration' => '+1 hours',
        'path' => CACHE,
        'prefix' => 'banana_core_',
    ]);
}
