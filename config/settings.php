<?php
return [
    'Settings' => [
        'App' => [
            'groups' => [
                'App.General' => [
                    'label' => __('Application'),
                ],
                'App.I18n' => [
                    'label' => __('Localization / Language'),
                ],
                'App.Cache' => [
                    'label' => __('Cache'),
                ],
                'App.Log' => [
                    'label' => __('Logging'),
                ],
                'App.Debug' => [
                    'label' => __('Debug'),
                ],
                'App.Error' => [
                    'label' => __('Error'),
                ],
                'App.Theme' => [
                    'label' => __('Theme'),
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
                    'help' => __('Full base url to your application. Leave empty to fallback to host name, which should be sufficient for most cases. E.g. https://my.domain.tld (no trailing slash). A wrong base url might break your application!'),
                    'default' => null,
                ],
                'App.defaultLocale' => [
                    'group' => 'App.I18n',
                    'type' => 'string',
                    'help' => __('Default language of your application. All translations will be based on the default language.'),
                    'default' => 'en',
                    'required' => true,
                ],
                'Site.theme' => [
                    'group' => 'App.Theme',
                    'type' => 'string',
                    'input' => [
                        'empty' => true,
                        'options' => function () {
                            $themes = \Cupcake\Cupcake::getThemes();

                            return array_combine($themes, $themes);
                        },
                    ],
                    'help' => 'Application encoding',
                    'default' => 'UTF-8',
                    'required' => false,
                ],
                'Cache.disable' => [
                    'group' => 'App.Cache',
                    'type' => 'boolean',
                    'label' => __('Disable cache'),
                    'help' => __('Disable cache system-wide'),
                    'default' => false,
                ],
                /*
                'Error.errorLevel' => [
                    'group' => 'App.Error',
                    'type' => 'int',
                    'input' => [
                        'options' => function () {
                            return [
                                E_ALL => __('Show all errors'),
                                (E_ALL & ~E_USER_DEPRECATED) => __('Disable deprecation warnings'),
                            ];
                        },
                    ],
                    'default' => false,
                ],
                */
                'debug' => [
                    'group' => 'App.Debug',
                    'type' => 'boolean',
                    'label' => __('Enable Debug mode'),
                    'help' => __('Enable the debug mode system-wide (Do not enable on production system!!'),
                    'default' => false,
                ],
                'DebugKit.enable' => [
                    'group' => 'App.Debug',
                    'type' => 'boolean',
                    'label' => __('Enable DebugKit'),
                    'help' => __('Enable the DebugKit plugin, if installed'),
                    'default' => false,
                ],
            ],
        ],
    ],
];
