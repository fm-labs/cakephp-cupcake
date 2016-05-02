<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/24/15
 * Time: 5:27 PM
 */

namespace Banana\View\Cell;

use Banana\Core\Banana;
use Cake\Database\Schema\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\View\Cell;
use Banana\Model\Table\PagesTable;


class HtmlElementModuleCell extends ModuleCell
{
    public $modelClass = false;

    public static $defaultParams = [
        'elementPath' => null,
    ];


    public static function inputs()
    {

        $options = Banana::getAvailableViewTemplates('Element/Modules/Html');
        return [
            'elementPath' => ['type' => 'select', 'options' => $options, 'empty' => true]
        ];
    }
}
