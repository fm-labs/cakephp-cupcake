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


class FlexSliderModuleCell extends ModuleCell
{
    public $modelClass = "Banana.Pages";

    public static $defaultParams = [
        'source' => 'folder', // folder|images
        'media_config' => '',
        'media_folder' => '',
        'media_images' => ''
    ];

}
