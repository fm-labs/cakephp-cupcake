<?php
use Banana\Lib\Banana;
use Cake\Core\Configure;

Configure::load('banana');

Banana::bootstrap();
//Banana::bootstrapConfigs();
Banana::bootstrapPlugins();
