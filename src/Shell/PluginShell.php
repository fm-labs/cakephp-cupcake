<?php

namespace Banana\Shell;

use Banana\Lib\BananaPlugin;
use Banana\Plugin\PluginLoader;
use Cake\Console\Shell;
use Cake\Core\App;

/**
 * Class PluginShell
 * @package Banana\Shell
 *
 * @todo Implement PluginShell ! Experimental !
 */
class PluginShell extends Shell
{
    /**
     * Print welcome message
     */
    protected function _welcome()
    {
        $this->hr();
        $this->out('Banana Plugin Shell');
        $this->hr();
    }

    /**
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('activate', [
            'parser' => [
                'arguments' => [
                    'name' => [
                        'required' => true
                    ]
                ]
            ],
            'help' => 'Install a banana plugin'
        ]);

        return $parser;
    }

    /**
     * @param null $pluginName
     */
    public function activate($pluginName = null)
    {
        $this->info('Activate plugin ' . $pluginName);
        PluginLoader::activate($pluginName);
    }
}
