<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/24/15
 * Time: 5:27 PM
 */

namespace Banana\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\View\Cell;
use Banana\Model\Table\PagesTable;


class TextHtmlModuleCell extends Cell
{
    public static $defaultParams = [
        'textHtml' => '<h1>Put your HTML here</h1>'
    ];

    public function display($module = null)
    {
        $params = array_merge(static::$defaultParams, $module->params_arr);

        $this->set($params);
        $this->set('module', $module);
    }

    public static function inputs()
    {
        return [
            'textHtml' => ['type' => 'htmleditor', 'default' => static::$defaultParams['textHtml']]
        ];
    }

    public static function defaults()
    {
        return static::$defaultParams;
    }
}
