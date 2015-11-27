<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/19/15
 * Time: 9:50 PM
 */

namespace Banana\View\Module;

use Banana\View\ViewModule;

class HtmlModule extends ViewModule
{
    protected $subDir = "";

    protected $params = [
        'html' => "",
        'strip_tags' => false,
        'allowed_tags' => null,
    ];

    public function display($params = [])
    {
        $this->setParams($params);

        $html = $this->params['html'];
        // strip tags
        if ($this->params['strip_tags'] === true) {
            $html = strip_tags($html, $this->params['allowed_tags']);
        }

        $this->set('html', $html);
    }

    public static function schema()
    {
        return parent::schema()->addFields([
            'html' => [
                'type' => 'text'
            ],
            'strip_tags' => [
                'type' => 'boolean'
            ],
            'allowed_tags' => [
                'type' => 'text'
            ]
        ]);
    }

    public static function inputs()
    {
        return [
            'html' => [
                'class' => 'htmleditor tinymce'
            ],
            'strip_tags' => [
                'class' => 'toggle',
                'default' => true
            ],
            'allowed_tags' => [
                'default' => 'a,strong,h1,h2,h3,h4,h5,h6'
            ]
        ];
    }
}
