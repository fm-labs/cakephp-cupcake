<?php

namespace Banana\Plugin;


use Cake\Event\EventManager;

interface PluginInterface
{
    /**
     * @param array $config
     * @return void
     */
    public function __invoke(array $config = []);
}