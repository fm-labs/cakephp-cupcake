<?php

namespace Cupcake;

use \Cake\Core\Plugin;

/**
 * Wrapper around CakePHP's Plugin class
 */
class PluginManager
{
    static public function getCollection(): \Cake\Core\PluginCollection
    {
        return Plugin::getCollection();
    }

    /**
     * @param string $pluginName
     * @return bool
     */
    static public function isLoaded(string $pluginName): bool
    {
        return Plugin::isLoaded($pluginName);
    }

    /**
     * Get plugin info
     *
     * @param string $pluginName Plugin name
     * @return array
     */
    static public function getPluginInfo(string $pluginName): array
    {
        $info = [];
        $info['name'] = $pluginName;
        $info['loaded'] = Plugin::isLoaded($pluginName);
        $info['path'] = Plugin::path($pluginName);
        $info['config'] = Plugin::configPath($pluginName);
        $info['classPath'] = Plugin::classPath($pluginName);
        //$info['registered'] = in_array($pluginName, Plugin::loaded());
        //$info['registered'] = true;

        $info['readme_file'] = self::getFilePath($pluginName, 'README.md');
        $info['license_file'] = self::getFilePath($pluginName, 'LICENCE');
        $info['contribute_file'] = self::getFilePath($pluginName, 'CONTRIB');
        $info['phpunit_file'] = self::getFilePath($pluginName, 'phpunit.xml.dist');
        $info['composer_file'] = self::getFilePath($pluginName, 'composer.json');
        $info['package_file'] = self::getFilePath($pluginName, 'package.json');

        $plugins = self::getCollection();
        $plugin = $plugins->has($pluginName) ? $plugins->get($pluginName) : null;

        //$info['handler_loaded'] = $plugin ? true : false;
        $info['handler_class'] = $plugin ? get_class($plugin) : null;
        $info['handler_bootstrap'] = $plugin ? $plugin->isEnabled('bootstrap') : null;
        $info['handler_routes'] = $plugin ? $plugin->isEnabled('routes') : null;
        //$info['handler_enabled'] = true;
        //$info['configuration_url'] = $plugin && $plugin instanceof BasePlugin ? $plugin->getConfigurationUrl() : null;
        //$info['configuration_url'] = null;

        return $info;
    }

    static public function getFilePath(string $pluginName, string $path): ?string
    {
        // @TODO Path security
        $filePath = dirname(Plugin::classPath($pluginName)) . DS . $path;
        if (is_file($filePath)) {
            return $filePath;
        }
        return null;
    }

    static public function getSourceFilePath(string $pluginName, string $path): ?string
    {
        // @TODO Path security
        $filePath = Plugin::classPath($pluginName) . $path;
        if (is_file($filePath)) {
            return $filePath;
        }
        return null;
    }

    static public function getConfigFilePath(string $pluginName, string $path): ?string
    {
        // @TODO Path security
        $filePath = Plugin::configPath($pluginName) . $path;
        if (is_file($filePath)) {
            return $filePath;
        }
        return null;
    }

    static public function getReadme(string $pluginName): ?string
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
}