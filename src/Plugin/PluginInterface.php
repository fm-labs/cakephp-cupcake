<?php

namespace Banana\Plugin;

/**
 * Interface PluginInterface
 *
 * @package Banana\Plugin
 */
interface PluginInterface
{
    /**
     * @param array $config
     * @return void
     */
    public function __invoke(array $config = []);
}
