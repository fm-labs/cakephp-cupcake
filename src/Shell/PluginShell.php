<?php

namespace Banana\Shell;


use Banana\Lib\BananaPlugin;
use Banana\Plugin\PluginLoader;
use Cake\Console\Shell;
use Cake\Core\App;

class PluginShell extends Shell
{

    protected function _welcome()
    {
        $this->hr();
        $this->out('Banana Plugin Shell');
        $this->hr();
    }

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

    public function activate($pluginName = null)
    {
        $this->info('Activate plugin ' . $pluginName);
        PluginLoader::activate($pluginName);
    }
}