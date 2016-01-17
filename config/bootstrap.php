<?php
use Banana\Lib\Banana;
use Cake\Core\Configure;

if (!Configure::read('Banana')) {
    die("Banana Plugin not configured");
}


Banana::bootstrap();
//Banana::bootstrapConfigs();
Banana::bootstrapPlugins();
