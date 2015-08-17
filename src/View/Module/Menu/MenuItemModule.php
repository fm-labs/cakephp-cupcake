<?php

namespace Banana\View\Module\Menu;

use Banana\View\ViewModule;
use Cake\Core\Exception\Exception;
use Cake\Form\Schema;
use Banana\Model\Table\PagesTable;
use Cake\Utility\Inflector;

/**
 * Class MenuListModule
 *
 * @package Banana\View\Module\Pages
 * @property PagesTable $Pages
 */
class MenuItemModule extends ViewModule
{
    protected $subDir = "Menu/";

    protected $params = [
        'page_id' => null,
        'item' => null,
    ];

    public function display($params = [])
    {
        $this->setParams($params);
    }

    public static function schema()
    {
        $schema = new Schema();
        $schema->addFields([
            'page_id' => [
                'type' => 'number'
            ],
        ]);
        return $schema;
    }

    public static function inputs()
    {
        return [
            'page_id' => [],
        ];
    }
}
