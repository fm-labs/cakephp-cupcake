<?php
return [
    'Backend.Plugin.Banana' => [
        'Menu' => [
            'title' => 'Content',
            'url' => ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'index'],
            'icon' => 'desktop',

            '_children' => [
                'pages' => [
                    'title' => 'Pages',
                    'url' => ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'index'],
                    'icon' => 'sitemap'
                ],
                'posts' => [
                    'title' => 'Posts',
                    'url' => ['plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'index'],
                    'icon' => 'edit'
                ],
                'galleries' => [
                    'title' => 'Galleries',
                    'url' => ['plugin' => 'Banana', 'controller' => 'Galleries', 'action' => 'index'],
                    'icon' => 'image'
                ],
                'page_layouts' => [
                    'title' => 'Layouts',
                    'url' => ['plugin' => 'Banana', 'controller' => 'PageLayouts', 'action' => 'index'],
                    'icon' => 'file'
                ],
                'module_builder' => [
                    'title' => 'Module Builder',
                    'url' => ['plugin' => 'Banana', 'controller' => 'ModuleBuilder', 'action' => 'index'],
                    'icon' => 'magic'
                ],
                'modules' => [
                    'title' => 'Modules',
                    'url' => ['plugin' => 'Banana', 'controller' => 'Modules', 'action' => 'index'],
                    'icon' => 'puzzle-piece'
                ],
                'content_modules' => [
                    'title' => 'Content Modules',
                    'url' => ['plugin' => 'Banana', 'controller' => 'ContentModules', 'action' => 'index'],
                    'icon' => 'object-group'
                ],
                'themes_manager' => [
                    'title' => 'Theme',
                    'url' => ['plugin' => 'Banana', 'controller' => 'ThemesManager', 'action' => 'index'],
                    'icon' => 'paint-brush'
                ],
            ]
        ]
    ]
];