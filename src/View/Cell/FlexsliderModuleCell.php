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


class FlexsliderModuleCell extends ModuleCell
{
    public $modelClass = "Banana.Pages";

    public static $defaultParams = [
        'source' => 'files', // folder|files
        'media_config' => 'default',
        'media_folder' => '',
        'media_images' => '',
        'desc_html' => ''
    ];


    public static function inputs()
    {
        return [
            'source' => ['options' => [
                'files' => 'Files',
                'folder' => 'Folder'
            ]],
            'media_config' => [],
            'media_folder' => [],
            'media_files' => ['type' => 'imagemodal'],
            'desc_html' => ['type' => 'htmleditor']
        ];
    }
}
