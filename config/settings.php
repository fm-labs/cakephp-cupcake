<?php
return [
    'Settings' => [
        'Banana' => [
            'site.enabled' => [
                'type' => 'boolean',
                'default' => false
            ],
            'site.title' => [
                'type' => 'string',
                'default' => 'BANANA:CAKE'
            ],
            'site.theme' => [
                'type' => 'string',
                'default' => null
            ],
            'posts.theme' => [
                'type' => 'string',
                'default' => null
            ],
            'session.timeout' => [
                'type' => 'int',
                'default' => 20
            ],
        ]
    ]
];
