<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/24/15
 * Time: 5:27 PM
 */

namespace Banana\View\Cell;

use Cake\Database\Schema\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\View\Cell;
use Banana\Model\Table\PagesTable;


class FlexsliderModuleCell extends ModuleCell
{
    public $modelClass = "Banana.Pages";

    public static $defaultParams = [
        'gallery_id' => null,
    ];


    public static function inputs()
    {
        $galleries = TableRegistry::get('Banana.Galleries')->find('list');

        return [
            'gallery_id' => ['type' => 'select', 'options' => $galleries]
        ];
    }
}
