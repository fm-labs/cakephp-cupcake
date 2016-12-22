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
            'sites' => [
                'title' => 'Sites',
                'url' => ['plugin' => 'Banana', 'controller' => 'Sites', 'action' => 'index'],
                'data-icon' => 'sitemap'
            ],
            'settings' => [
                'title' => 'Settings',
                'url' => ['plugin' => 'Banana', 'controller' => 'Settings', 'action' => 'index'],
                'data-icon' => 'gears',
            ],
        ]
    ]
];