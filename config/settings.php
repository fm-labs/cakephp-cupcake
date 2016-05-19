<?php
return [
    'Settings' => [
        'Site.debug' => [
            'description' => 'Enable / Disable debugging for Banana',
            'type' => 'boolean',
            'default' => false
        ],
        'Site.enabled' => [
            'type' => 'boolean',
            'default' => false
        ],
        'Site.title' => [
            'type' => 'string',
            'default' => 'Untitled Site'
        ],
        'Site.theme' => [
            'type' => 'string',
            'default' => null
        ],
        /*
        'Site.Editor.default.imageList' => [
            'type' => 'string', // url
            'default' => '/banana/data/imageList'
        ],
        'Site.Editor.default.linkList' => [
            'type' => 'string', // url
            'default' => '/banana/data/linkList'
        ],
        */
    ]
];
