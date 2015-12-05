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
use Media\Lib\Media\MediaManager;


class ImageModuleCell extends ModuleCell
{
    public static $defaultParams = [
        'image_file' => ''
    ];

    public static function inputs()
    {
        return [
            'image_file' => ['type' => 'imageselect', 'options' => '@default']
        ];
    }

    public function display()
    {
        parent::display();

        if ($this->params['image_file']) {
            $mm = MediaManager::get('default');
            $imageUrl = $mm->getFileUrl($this->params['image_file']);
            $this->set('image_url', $imageUrl);
        }
    }
}
