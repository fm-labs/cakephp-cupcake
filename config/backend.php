<?php
return [
    'Backend.Plugin.Banana.Menu' => [

        'app' => [
            [
                'title' => 'Dashboard',
                'url' => ['plugin' => 'Banana', 'controller' => 'Dashboard', 'action' => 'index'],
                'data-icon' => 'cubes'
            ]
        ],

        'system' => [
            [
                'title' => 'Sites',
                'url' => ['plugin' => 'Banana', 'controller' => 'Sites', 'action' => 'index'],
                'data-icon' => 'sitemap'
            ]
        ]
    ]
];