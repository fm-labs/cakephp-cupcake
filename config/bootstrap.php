<?php
use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\Configure;
use Cake\Database\Type;
use Cake\Error\ErrorHandler;
use Cake\Network\Request;


// Set the full base URL.
// This URL is used as the base of all absolute links.
if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}


/**
 * Set server timezone to UTC. You can change it to another timezone of your
 * choice but using UTC makes time calculations / conversions easier.
 */
date_default_timezone_set('UTC'); // @TODO Make default timezone configurable

/**
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/**
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', 'de'); //@TODO Make default locale configurable


/**
 * Setup detectors for mobile and tablet.
 * @todo Remove mobile request detectors from banana. Move to site's bootstrap
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
//Type::map('json', 'Banana\Database\Type\JsonType'); // obsolete since CakePHP 3.3
Type::map('serialize', 'Banana\Database\Type\SerializeType');

/**
 * Enable default locale format parsing.
 * This is needed for matching the auto-localized string output of Time() class when parsing dates.
 */
Type::build('datetime')->useLocaleParser();


$isCli = php_sapi_name() === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();

    // Include the CLI bootstrap overrides.
    //require $this->configDir . '/bootstrap_cli.php';
    //} elseif (class_exists('\Gourmet\Whoops\Error\WhoopsHandler')) {
    // Out-of-the-box support for "Whoops for CakePHP3" by "gourmet"
    // https://github.com/gourmet/whoops
    //    (new \Gourmet\Whoops\Error\WhoopsHandler(Configure::read('Error')))->register();
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}



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
 */
\Backend\View\Helper\FormatterHelper::register('status', function ($val, $extra, $params, $view) {
    $view->loadHelper('Banana.Status');
    return $view->Status->label($val);
});

