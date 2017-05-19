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
 * Core Banana plugins (required)
 */
//Plugin::load('Backend', ['bootstrap' => true, 'routes' => true]);
//Plugin::load('User', ['bootstrap' => true, 'routes' => true]);
//Plugin::load('Tree', ['bootstrap' => true, 'routes' => false]);

/**
 * Load themes
 */

/**
 * User plugins
 * Plugins with an plugin config in config/plugins will be loaded now
 */
//PluginLoader::loadAll();

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
//\Cake\Event\EventManager::instance()->on(new \Banana\Event\BackendEventListener());