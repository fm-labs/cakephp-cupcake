<?php
use Cake\Cache\Cache;

/**
 * Cache config
 */
if (!Cache::config('banana')) {
    Cache::config('banana', [
        'className' => 'File',
        'duration' => '+1 hours',
        'path' => CACHE,
        'prefix' => 'banana_core_'
    ]);
}
