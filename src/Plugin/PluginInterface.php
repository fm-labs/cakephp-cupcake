<?php

namespace Banana\Plugin;


use Cake\Event\EventManager;

interface PluginInterface
{
    /**
     * @param EventManager $eventManager
     * @return $this
     */
    public function registerEvents(EventManager $eventManager);

    /**
     * @param array $config
     * @return void
     */
    public function __invoke(array $config = []);
}