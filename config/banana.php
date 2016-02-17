<?php
return [
    'Banana' => [
        'Router' => [
            'disableFrontendRoutes' => false,
            'disableAdminRoutes' => false,
            'enablePrettyUrls' => true,
            'forceCanonical' => false,
        ],
        'HtmlEditor' => [
            'default' => [
                'convert_urls' => false,
                'image_list_url' => ['plugin' => 'Banana', 'controller' => 'HtmlEditor', 'action' => 'imageList'],
                'link_list_url' => ['plugin' => 'Banana', 'controller' => 'HtmlEditor', 'action' => 'linkList']
            ],
        ],
        'Frontend' => [
            'theme' => null
        ],
        'Modules' => [
        ]
    ]
];