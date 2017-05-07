<?php
use Banana\Plugin\PluginLoader;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Database\Type;
use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Network\Request;
use Cake\Utility\Security;
use Cake\Routing\DispatcherFactory;



// When debug = false the metadata cache should last
// for a very very long time, as we don't want
// to refresh the cache while users are doing requests.
if (!Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+1 years');
    Configure::write('Cache._cake_core_.duration', '+1 years');
}

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
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

// Include the CLI bootstrap overrides.
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/**
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
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


Cache::config(Configure::consume('Cache'));
ConnectionManager::config(Configure::consume('Datasources'));
Log::config(Configure::consume('Log'));
Security::salt(Configure::consume('Security.salt'));
Email::configTransport(Configure::consume('EmailTransport'));
Email::config(Configure::consume('Email'));


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

// Only try to load DebugKit in development mode
// Debug Kit should not be installed on a production system
if (Configure::read('debug')) {
    Plugin::load('DebugKit', ['bootstrap' => true]);
}

/**
 * Core Banana plugins (required)
 */
Plugin::load('Backend', ['bootstrap' => true, 'routes' => true]);
Plugin::load('User', ['bootstrap' => true, 'routes' => true]);
Plugin::load('Tree', ['bootstrap' => true, 'routes' => false]);

/**
 * Load themes
 */
if (Configure::check('Site.theme')) {
    try {
        Plugin::load(Configure::read('Site.theme'), ['bootstrap' => true, 'routes' => true]);
    } catch (\Cake\Core\Exception\Exception $ex) {
        die ($ex->getMessage());
    }
}

/**
 * User plugins
 * Plugins with an plugin config in config/plugins will be loaded now
 */
PluginLoader::loadAll();

/**
 * Themes
 */
//BananaTheme::loadAll();


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
 * Attach event listeners
 */
\Cake\Event\EventManager::instance()->on(new \Banana\Event\BackendEventListener());

/**
 * Settings
try {
    Configure::config('settings', new \Banana\Configure\Engine\SettingsConfig());
    Configure::load('global', 'settings');
} catch (\Exception $ex) {
    die("Failed to load settings: " . $ex->getMessage());
}
*/