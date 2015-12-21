<?php
namespace Banana\View\Cell;

use Cake\ORM\TableRegistry;
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

    public function display()
    {
        if (empty($this->params['menu'])) {
            $this->loadModel('Banana.Pages');

            $startNodeId = $this->_getStartNodeId();
            if ($startNodeId) {
                $children = $this->Pages
                    ->find('children', ['for' => $startNodeId])
                    ->find('threaded')
                    ->toArray();

                $this->params['menu'] = $this->_buildMenu($children);
            } else {
                debug("Start node not found");
                $this->params['menu'] = [];
            }
        }

        $this->params['element_path'] = ($this->params['element_path']) ?: 'Banana.Modules/PagesMenu/menu_list';

        //$tree = $this->Pages->find('treeList')->toArray();
        //$this->set('tree', $tree);
        //$this->set('children', $children);
        //$this->set($params);
        //$this->render('other');
        $this->set('params', $this->params);
    }

    protected function _buildMenu($children)
    {
        $menu = [];
        foreach ($children as $child) {
            $isActive = false;
            $attr = ['class' => $child->cssclass];
            if ($child->hide_in_nav === true) {
                continue;

            } elseif ($this->request->param('page_id') == $child->id) {
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
                $attr['class'] .= ' active';
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
        } elseif ($this->params['start_node'] < 0) {
            $nodeId = $this->page_id;
        } else {
            //@TODO: Use custom finder to find root node (Pages->findRootNode())
            $rootNode = $this->Pages->find()->where(['parent_id IS NULL'])->first();
            if (!$rootNode) {
                throw new \Exception('MenuListModule: No root node found');
            }
            $nodeId = $rootNode->id;
        }
        return $nodeId;
    }
}
