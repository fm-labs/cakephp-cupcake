<?php
namespace Banana\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\View\Cell;
use Banana\Model\Table\PagesTable;


class PagesMenuModuleCell extends ModuleCell
{
    public $modelClass = "Banana.Pages";

    public static $defaultParams = [
        'menu' => [],
        'start_node' => 0,
        'depth' => 1,
        'level' => 0,
        'class' => '',
        'element_path' => null
    ];

    protected $_index = [];
    protected $_activeIndex;
    protected $_depth = 0;

    public function display()
    {
        if (empty($this->params['menu'])) {
            $this->loadModel('Banana.Pages');

            $startNodeId = $this->_getStartNodeId();
            if ($startNodeId) {
                $children = $this->Pages
                    ->find('children', ['for' => $startNodeId])
                    ->find('threaded')
                    ->orderAsc('lft')
                    ->contain([])
                    ->toArray();

                $this->params['menu'] = $this->_buildMenu($children);
            } else {
                debug("Start node not found");
                $this->params['menu'] = [];
            }
        }

        $this->params['element_path'] = ($this->params['element_path']) ?: 'Banana.Modules/PagesMenu/menu_list';

        $this->set('index', $this->_index);
        $this->set('activeIndex', $this->_activeIndex);-
        $this->set('activePageId', $this->request->param('page_id'));
        $this->set('params', $this->params);
    }

    protected function _buildMenu($children)
    {
        $this->_depth++;
        $menu = [];
        foreach ($children as $child) {
            $isActive = false;
            $class = $child->cssclass;

            if ($child->isPageHiddenInNav()) {
                continue;

            } elseif (!$child->isPagePublished()) {
                continue;

            //} elseif ($this->request->param('page_id') == $child->id) {
            //    $isActive = true;

            } elseif ($child->type == 'controller') {
                $plugin = $this->request->param('plugin');
                $controller = $this->request->param('controller');
                $needle = ($plugin)
                    ? Inflector::camelize($plugin) . '.' . Inflector::camelize($controller)
                    : Inflector::camelize($controller);

                //if ($child->redirect_location == $needle) {
                //    $isActive = true;
                //}
            }

            if ($isActive) {
                $class .= ' active';
            }

            $itemPageId = $child->getPageId();
            $item = [
                'title' => $child->getPageTitle(),
                'url' => $child->getPageUrl(),
                'class' => $class,
                '_children' => []
            ];

            $indexKey = count($this->_index) . ':' . Router::url($item['url'], true);
            $this->_index[$indexKey] = str_repeat('_', $this->_depth - 1) . $item['title'];
            //if ($isActive) {
            //    $this->_activeIndex = $indexKey;
            //}

            /*
            if ($child->children) {
                $item['_children'] = $this->_buildMenu($child->children);
            }
            */

            if ($this->_depth <= $this->params['depth'] && $child->getPageChildren()) {
                $item['_children'] = $this->_buildMenu($child->getPageChildren());
            }

            $menu[] = $item;
        }

        $this->_depth--;
        return $menu;
    }

    protected function _getStartNodeId()
    {
        if ($this->params['start_node'] > 0) {
            $nodeId = $this->params['start_node'];
        } elseif ($this->params['start_node'] < 0) {
            $nodeId = $this->refid;
        } else {
            $rootNode = $this->Pages->findHostRoot();
            if (!$rootNode) {
                throw new \Exception('MenuListModule: No root node found');
            }
            $nodeId = $rootNode->id;
        }
        return $nodeId;
    }
}
