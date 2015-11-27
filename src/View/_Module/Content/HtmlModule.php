<?php

namespace Banana\View\Module\Content;

use Banana\View\Module\HtmlModule as BaseHtmlModule;
use Cake\Form\Schema;

/**
 * Class HtmlPageModule
 *
 * A pre-configured HtmlModule with only a single html input field
 *
 * @package Banana\View\Module\Pages
 */
class HtmlModule extends BaseHtmlModule
{
    protected $subDir = "Content/";

    protected $params = [
        'html' => "",
        'strip_tags' => false,
        'allowed_tags' => '',
    ];

    public function display($params = [])
    {
        $params['strip_tags'] = false;
        $params['allowed_tags'] = '';
        parent::display($params);
    }

    public static function schema()
    {
        $schema = new Schema();
        $schema->addFields([
            'html' => [
                'type' => 'text'
            ],
        ]);
        return $schema;
    }

    public static function inputs()
    {
        return [
            'html' => [
                'type' => 'htmleditor'
                //'class' => 'htmleditor tinymce'
            ],
        ];
    }
}
