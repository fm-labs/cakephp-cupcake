<?php
return [
    'Settings' => [
        'Banana.debug' => [
            'description' => 'Enable / Disable debugging for Banana',
            'type' => 'boolean',
            'default' => false
        ],
        'Banana.Site.enabled' => [
            'type' => 'boolean',
            'default' => false
        ],
        'Banana.Site.title' => [
            'type' => 'string',
            'default' => 'Untitled Site'
        ],
        'Banana.Frontend.theme' => [
            'type' => 'string',
            'default' => null
        ],
        /*
        'Banana.Editor.default.imageList' => [
            'type' => 'string', // url
            'default' => '/banana/data/imageList'
        ],
        'Banana.Editor.default.linkList' => [
            'type' => 'string', // url
            'default' => '/banana/data/linkList'
        ],
        */
    ]
];
