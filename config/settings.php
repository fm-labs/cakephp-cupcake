<?php
return [
    'Settings' => [
        'App' => [
            'groups' => [
                'App.General' => [
                    'label' => __d('cupcake', 'Application'),
                ],
                'App.I18n' => [
                    'label' => __d('cupcake', 'Localization / Language'),
                ],
                'App.Cache' => [
                    'label' => __d('cupcake', 'Cache'),
                ],
                'App.Log' => [
                    'label' => __d('cupcake', 'Logging'),
                ],
                'App.Debug' => [
                    'label' => __d('cupcake', 'Debug'),
                ],
                'App.Error' => [
                    'label' => __d('cupcake', 'Error'),
                ],
                'App.Theme' => [
                    'label' => __d('cupcake', 'Theme'),
                ],
            ],

            'schema' => [
                'App.defaultTimezone' => [
                    'group' => 'App.General',
                    'type' => 'string',
                    'help' => 'Server timezone',
                    'default' => 'UTC',
                    'required' => true,
                ],
                'App.encoding' => [
                    'group' => 'App.General',
                    'type' => 'string',
                    'help' => 'Application encoding',
                    'default' => 'UTF-8',
                    'required' => true,
                ],
                'App.fullBaseUrl' => [
                    'group' => 'App.General',
                    'type' => 'string',
                    'help' => __d('cupcake', 'Full base url to your application. Leave empty to fallback to host name, which should be sufficient for most cases. E.g. https://my.domain.tld (no trailing slash). A wrong base url might break your application!'),
                    'default' => null,
                ],
                'App.defaultLocale' => [
                    'group' => 'App.I18n',
                    'type' => 'string',
                    'help' => __d('cupcake', 'Default language of your application. All translations will be based on the default language.'),
                    'default' => 'en',
                    'required' => true,
                ],
                'Theme.name' => [
                    'group' => 'App.Theme',
                    'type' => 'string',
//                    'input' => [
//                        'empty' => true,
//                        'options' => function () {
//                            $themes = \Cupcake\Cupcake::getThemes();
//                            return array_combine($themes, $themes);
//                        },
//                    ],
                    'help' => 'Theme name',
                    'default' => '',
                    'required' => false,
                ],
                'Theme.layout' => [
                    'group' => 'App.Theme',
                    'type' => 'string',
                    'help' => 'Theme default layout',
                    'default' => '',
                    'required' => false,
                ],
                'Cache.disable' => [
                    'group' => 'App.Cache',
                    'type' => 'boolean',
                    'label' => __d('cupcake', 'Disable cache'),
                    'help' => __d('cupcake', 'Disable cache system-wide'),
                    'default' => false,
                ],
                /*
                'Error.errorLevel' => [
                    'group' => 'App.Error',
                    'type' => 'int',
                    'input' => [
                        'options' => function () {
                            return [
                                E_ALL => __d('cupcake', 'Show all errors'),
                                (E_ALL & ~E_USER_DEPRECATED) => __d('cupcake', 'Disable deprecation warnings'),
                            ];
                        },
                    ],
                    'default' => false,
                ],
                */
//                'debug' => [
//                    'group' => 'App.Debug',
//                    'type' => 'boolean',
//                    'label' => __d('cupcake', 'Enable Debug mode'),
//                    'help' => __d('cupcake', 'Enable the debug mode system-wide (Do not enable on production system!!'),
//                    'default' => false,
//                ],
                'DebugKit.enable' => [
                    'group' => 'App.Debug',
                    'type' => 'boolean',
                    'label' => __d('cupcake', 'Enable DebugKit'),
                    'help' => __d('cupcake', 'Enable the DebugKit plugin, if installed'),
                    'default' => false,
                ],
            ],
        ],
    ],
];
