<?php
declare(strict_types=1);

namespace Cupcake;

use Cake\Collection\Collection;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Core\PluginCollection;

/**
 * Wrapper around CakePHP's Plugin class
 */
class PluginManager
{
    private static mixed $composerInfo = null;
    private static mixed $composerPackages = null;

    public static function getCollection(): PluginCollection
    {
        return Plugin::getCollection();
    }

    public static function findLoadedPlugins()
    {
        $loadedPlugins = [];
        foreach (Plugin::loaded() as $name) {
            $loadedPlugins[$name] = [
                'name' => $name,
                'path' => Plugin::path($name),
            ];
        }

        return $loadedPlugins;
    }

    public static function findLocalPlugins(): array
    {
        $plugins = [];
        $pluginFinder = function ($path) use (&$plugins): void {
            if (!is_dir($path)) {
                return;
            }

            $files = scandir($path);
            foreach ($files as $f) {
                $pluginPath = rtrim($path, '/') . '/' . $f;
                if ($f == '.' || $f == '..' || !is_dir($pluginPath)) {
                    continue;
                }

                if (!file_exists($pluginPath . DS . 'composer.json')) {
                    // Maybe it's a vendor plugin?
                    // Let's check one more level
                    $_files = scandir($pluginPath);
                    foreach ($_files as $_f) {
                        $_pluginPath = rtrim($pluginPath, '/') . '/' . $_f;
                        if ($_f == '.' || $_f == '..' || !is_dir($_pluginPath)) {
                            continue;
                        }
                        if (!file_exists($_pluginPath . DS . 'composer.json')) {
                            continue;
                        }

                        $_pluginName = sprintf('%s/%s', $f, $_f);
                        $plugins[$_pluginName] = [
                            'name' => $_pluginName,
                            'path' => $_pluginPath,
                            'local' => 1,
                        ];
                    }
                    continue;
                }

                $plugins[$f] = [
                    'name' => $f,
                    'path' => $pluginPath,
                    'local' => 1,
                ];
            }
        };

        // find available plugins from known plugin folders
        foreach (App::path('plugins') as $path) {
            $pluginFinder($path);
        }

        return $plugins;
    }

    public static function findVendorPlugins(): array
    {
        $configured = Configure::read('plugins');
        $vendorPlugins = [];
        foreach ($configured as $pluginName => $pluginPath) {
            $vendorPlugins[$pluginName] = [
                'name' => $pluginName,
                'path' => $pluginPath,
            ];
        }

        return $vendorPlugins;
    }

    /**
     * @param string $pluginName
     * @return bool
     */
    public static function isLoaded(string $pluginName): bool
    {
        return Plugin::isLoaded($pluginName);
    }

    /**
     * Get plugin info
     *
     * @param string $pluginName Plugin name
     * @return array
     */
    public static function getPluginInfo(string $pluginName): array
    {
        $info = [];
        $info['name'] = $pluginName;
        $info['loaded'] = Plugin::isLoaded($pluginName);

        // meta
        $info['composer_name'] = self::getComposerPackageName($pluginName);
        $info['version'] = self::getInstalledComposerPackageVersion($pluginName);

        // instance
        $instanceInfo = null;
        $plugins = self::getCollection();
        $plugin = $info['loaded'] ? $plugins->get($pluginName) : null;
        if ($plugin) {
            $instanceInfo = [];
            $info['path'] = Plugin::path($pluginName);
            $info['config'] = Plugin::configPath($pluginName);
            $info['classPath'] = Plugin::classPath($pluginName);

            $instanceInfo['class'] = get_class($plugin);
            $instanceInfo['bootstrap'] = $plugin->isEnabled('bootstrap');
            $instanceInfo['routes'] = $plugin->isEnabled('routes');
            //$instanceInfo['handler_loaded'] = $plugin ? true : false;
            //$instanceInfo['handler_enabled'] = true;
            //$instanceInfo['configuration_url'] = $plugin && $plugin instanceof BasePlugin ? $plugin->getConfigurationUrl() : null;
            //$instanceInfo['configuration_url'] = null;
        }
        //$info['loaded'] = Plugin::isLoaded($pluginName);
        $info['instance'] = $instanceInfo;

        // files
        $files = [];
        $files['readme'] = self::getFilePath($pluginName, 'README.md');
        $files['license'] = self::getFilePath($pluginName, 'LICENCE');
        $files['contrib'] = self::getFilePath($pluginName, 'CONTRIB');
        $files['phpunit'] = self::getFilePath($pluginName, 'phpunit.xml.dist');
        $files['phpstan'] = self::getFilePath($pluginName, 'phpstan.neon');
        $files['composer'] = self::getFilePath($pluginName, 'composer.json');
        $files['package'] = self::getFilePath($pluginName, 'package.json');
        $info['files'] = $files;

        return $info;
    }

    public static function getFilePath(string $pluginName, string $path): ?string
    {
        // @TODO Path security
        $filePath = dirname(Plugin::classPath($pluginName)) . DS . $path;
        if (is_file($filePath)) {
            return $filePath;
        }

        return null;
    }

    public static function getSourceFilePath(string $pluginName, string $path): ?string
    {
        // @TODO Path security
        $filePath = Plugin::classPath($pluginName) . $path;
        if (is_file($filePath)) {
            return $filePath;
        }

        return null;
    }

    public static function getConfigFilePath(string $pluginName, string $path): ?string
    {
        // @TODO Path security
        $filePath = Plugin::configPath($pluginName) . $path;
        if (is_file($filePath)) {
            return $filePath;
        }

        return null;
    }

    public static function getReadme(string $pluginName): ?string
    {
        $filePath = self::getFilePath($pluginName, 'README.md');
        if ($filePath && is_file($filePath)) {
            $content = file_get_contents($filePath);
//            $converter = new \League\CommonMark\GithubFlavoredMarkdownConverter([
//                'html_input' => 'strip',
//                'allow_unsafe_links' => false,
//            ]);
//            return $converter->convertToHtml($content);
            return $content;
        }

        return null;
    }

    /**
     * Read plugin version from composer.json
     *
     * @param string $pluginName
     * @return string|null
     */
    public static function getComposerPackageName(string $pluginName): ?string
    {
        $data = self::getPluginComposerInfo($pluginName);

        return $data['name'] ?? null;
    }

    public static function getInstalledComposerPackageVersion(string $pluginName): ?string
    {
        self::loadInstalledComposerPackages();
        //debug(static::$composerPackages);
        $packageName = self::getComposerPackageName($pluginName);
        $package = (new Collection(static::$composerPackages))
            ->filter(function ($item) use ($packageName) {
                return $item['name'] == $packageName;
            })
            ->first();
        if ($package) {
            return $package['version'];
        }

        return '0.0.0';
    }

    protected static function getPluginComposerInfo(string $pluginName)
    {
        // composer
        $composerFile = self::getFilePath($pluginName, 'composer.json');
        if (!$composerFile) {
            return null;
        }
        $contents = file_get_contents($composerFile);

        return json_decode($contents, true);
    }

    protected static function loadComposerInfo(): void
    {
        if (self::$composerInfo !== null) {
            return;
        }

        // composer
        $composerFile = ROOT . DS . 'composer.json';
        if (!$composerFile) {
            return;
        }
        $contents = file_get_contents($composerFile);
        static::$composerInfo = json_decode($contents, true);
    }

    protected static function loadInstalledComposerPackages(): void
    {
        if (self::$composerPackages !== null) {
            return;
        }

        // installed
        $installedFile = ROOT . DS . 'vendor' . DS . 'composer' . DS . 'installed.json';
        if (!$installedFile) {
            return;
        }
        $contents = file_get_contents($installedFile);
        $data = json_decode($contents, true);
        static::$composerPackages = $data['packages'] ?? [];
    }
}
