<?php

namespace Banana\Shell;


use Banana\Lib\BananaPlugin;
use Cake\Console\Shell;
use Cake\Core\App;

class SetupShell extends Shell
{

    protected function _welcome()
    {
        $this->hr();
        $this->out('Banana Setup Shell');
        $this->hr();
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('installPlugin', [
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

    public function installPlugin($pluginName = null)
    {
        $this->info('Install plugin ' . $pluginName);

        BananaPlugin::install($pluginName);

        $this->success('Installation complete');
    }
}