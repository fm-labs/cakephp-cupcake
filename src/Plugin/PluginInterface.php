<?php
namespace Banana\Plugin;

use Banana\Application;

interface PluginInterface
{
    public function bootstrap(Application $app);
}