<?php
use Cake\Core\Configure;
use Cake\Database\Type;
use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Error\ErrorHandler;
use Cake\Network\Request;
use Cake\Routing\DispatcherFactory;



/**
 * Set server timezone to UTC. You can change it to another timezone of your
 * choice but using UTC makes time calculations / conversions easier.
 */
date_default_timezone_set('UTC');

/**
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/**
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', 'de');

/**
 * Register application error and exception handlers.
 */
$isCli = php_sapi_name() === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
//} elseif (class_exists('\Gourmet\Whoops\Error\WhoopsHandler')) {
    // Out-of-the-box support for "Whoops for CakePHP3" by "gourmet"
    // https://github.com/gourmet/whoops
//    (new \Gourmet\Whoops\Error\WhoopsHandler(Configure::read('Error')))->register();
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

// Include the CLI bootstrap overrides.
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}


/**
 * Setup detectors for mobile and tablet.
 */
Request::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isMobile();
});
Request::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isTablet();
});


/**
 * Register database types
 */
//Type::map('json', 'Banana\Database\Type\JsonType');
Type::map('serialize', 'Banana\Database\Type\SerializeType');

/**
 * Enable default locale format parsing.
 * This is needed for matching the auto-localized string output of Time() class when parsing dates.
 */
Type::build('datetime')->useLocaleParser();


/**
 * Connect middleware/dispatcher filters.
 */
DispatcherFactory::add('Asset');
DispatcherFactory::add('Routing');
//DispatcherFactory::add('Content.ContentLocaleSelector');
DispatcherFactory::add('ControllerFactory');

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