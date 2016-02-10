<?php
use Banana\Core\Banana;
use Cake\Core\Configure;

if (!Configure::read('Banana')) {
    die("Banana Plugin not configured");
}


Banana::bootstrap();
//Banana::bootstrapConfigs();
Banana::bootstrapPlugins();
