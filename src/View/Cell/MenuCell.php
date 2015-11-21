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


class MenuCell extends Cell
{
    public $modelClass = "Banana.Pages";

    protected $params = [
        'menu' => null,
        'start_node' => 0,
        'depth' => 1,
        'level' => 0,
        'class' => ''
    ];

    public function display($params = [])
    {

        $params = array_merge($this->params, $params);

        if (!$params['menu']) {
            $this->loadModel('Banana.Pages');


            $this->_getStartNodeId();
            $children = $this->Pages
                ->find('children', ['for' => $this->startNodeId])
                ->find('threaded')
                ->toArray();

            $params['menu'] = $this->_buildMenu($children);
        }


        //$tree = $this->Pages->find('treeList')->toArray();
        //$this->set('tree', $tree);
        //$this->set('children', $children);
        $this->set($params);
        //$this->render('other');
    }

    protected function _buildMenu($children)
    {
        $menu = [];
        foreach ($children as $child) {
            $isActive = false;
            $attr = ['class' => ''];
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
                'url' => $child->url,
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
}
