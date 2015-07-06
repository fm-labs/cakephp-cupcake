<?php

namespace Banana\View\Module\Content;

use Banana\View\Module\HtmlModule as BaseHtmlModule;
use Banana\View\ViewModule;
use Cake\Form\Schema;

/**
 * Class HtmlPageModule
 *
 * A pre-configured HtmlModule with only a single html input field
 *
 * @package Banana\View\Module\Pages
 */
class ImageModule extends ViewModule
{
    protected $subDir = "Content/";

    protected $params = [
        'src' => '',
        'alt' => '',
        'title' => '',
    ];

    public function display($params = [])
    {
        $this->setParams($params);
    }

    public static function schema()
    {
        $schema = new Schema();
        $schema->addFields([
            'src' => [
                'allowEmpty' => false
            ],
            'alt' => [],
            'title' => [],
        ]);
        return $schema;
    }

    public static function inputs()
    {
        return [
            'src' => [
                'class' => 'select-image'
            ],
            'alt' => [],
            'title' => [],
        ];
    }
}
