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

/**
 * Register FormatterHelper formatters
 * @TODO Remove
 */
\Backend\View\Helper\FormatterHelper::register('status', function ($val, $extra, $params, $view) {
    $view->loadHelper('Banana.Status');
    return $view->Status->label($val);
});

