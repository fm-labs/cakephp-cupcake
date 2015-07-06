<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/24/15
 * Time: 1:13 AM
 */

namespace Banana\View\Module\Demo;

use Banana\View\ViewModule;

class KitchenSinkModule extends ViewModule
{
    public $subDir = "Demo/";

    public $params = [
        'string' => null,
        'text' => null,
        'html' => null,
    ];

    public function display($params = [])
    {
        $this->setParams($params);
    }

    public static function schema()
    {
        return parent::schema()->addFields([
            'string' => [
                'type' => 'string'
            ],
            'text' => [
                'type' => 'text'
            ],
            'html' => [
                'type' => 'text'
            ],
            'boolean' => [
                'type' => 'boolean'
            ]
        ]);
    }

    public static function inputs()
    {
        return [
            'string' => [
                'default' => 'Hi, I\'m some default string',
                'label' => 'The label can be changed too'
            ],
            'text' => [
                'default' => 'Hi. This is plain text'
            ],
            'html' => [
                'class' => 'text text-html tinymce',
                'default' => '<strong>Hrrrr, this is strong html</strong>'
            ],
            'boolean' => [
                'default' => true,
            ]
        ];
    }
}