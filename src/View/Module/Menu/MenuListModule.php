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
class MenuListModule extends ViewModule
{
    protected $subDir = "Menu/";

    protected $params = [
        'start_node' => 0,
        'depth' => 1
    ];

    protected $startNodeId;

    public function display($params = [])
    {

        $this->loadModel('Banana.Pages');

        $tree = $this->Pages->find('treeList')->toArray();

        $this->_getStartNodeId();
        $children = $this->Pages
            ->find('children', ['for' => $this->startNodeId])
            ->find('threaded')
            ->toArray();

        $menu = $this->_buildMenu($children);

        $this->set('tree', $tree);
        $this->set('children', $children);
        $this->set('menu', $menu);
    }

    protected function _buildMenu($children)
    {
        $menu = [];
        foreach ($children as $child) {
            $isActive = false;
            $attr = ['class' => 'blaa '];
            if ($this->request->param('page_id') == $child->id) {
                $isActive = true;

            } elseif ($child->type == 'controller') {
                $plugin = $this->request->param('plugin');
                $controller = $this->request->param('controller');
                $needle = ($plugin)
                    ? Inflector::camelize($plugin) . '.' . Inflector::camelize($controller)
                    : Inflector::camelize($controller);

                if ($child->redirect_location == $needle) {
                    $isActive = true;
                }
            }

            if ($isActive) {
                $attr['class'] .= 'active ';
            }

            $item = [
                'title' => $child->title,
                'url' => ['plugin' => 'Banana', 'controller' => 'Pages', 'action' => 'view', 'slug' => $child->slug, 'id' => $child->id],
                'attr' => $attr,
                '_children' => []
            ];
            if ($child->children) {
                $item['_children'] = $this->_buildMenu($child->children);
            }
            $menu[] = $item;
        }
        return $menu;
    }

    protected function _getStartNodeId()
    {
        if ($this->params['start_node'] > 0) {
            $nodeId = $this->params['start_node'];
        } else {
            $rootNode = $this->Pages->find()->where(['parent_id IS NULL'])->first();
            if (!$rootNode) {
                throw new Exception('MenuListModule: No root node found');
            }
            $nodeId = $rootNode->id;
        }
        return $this->startNodeId = $nodeId;
    }

    protected function _getMenuList()
    {

    }

    public static function schema()
    {
        $schema = new Schema();
        $schema->addFields([
            'start_node' => [
                'type' => 'number'
            ],
            'depth' => [
                'type' => 'number'
            ],
        ]);
        return $schema;
    }

    public static function inputs()
    {
        return [
            'start_node' => ['default' => 0],
            'depth' => ['default' => 1],
        ];
    }
}
