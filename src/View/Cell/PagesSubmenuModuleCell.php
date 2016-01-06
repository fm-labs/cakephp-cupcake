<?php
namespace Banana\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\View\Cell;
use Banana\Model\Table\PagesTable;


class PagesSubmenuModuleCell extends PagesMenuModuleCell
{
    public $modelClass = "Banana.Pages";

    protected function _getStartNodeId()
    {
        if ($this->params['start_node'] > 0) {
            // Predefined start node
            $nodeId = $this->params['start_node'];

        } elseif ($this->params['start_node'] < 0) {

            // Determine start node for level
            $level = $this->Pages->getLevel($this->refid);
            if ($level > $this->params['level']) {
                $path = $this->Pages->find('path', ['for' => $this->refid])->toArray();
                $nodeId = $path[$this->params['level']]->id;
            } else {
                $nodeId = $this->refid;
            }


        } else {
            /*
            //@TODO: Use custom finder to find root node (Pages->findRootNode())
            $rootNode = $this->Pages->find()->where(['parent_id IS NULL'])->first();
            if (!$rootNode) {
                throw new \Exception('MenuListModule: No root node found');
            }
            $nodeId = $rootNode->id;
            */
            $nodeId = null;
        }
        return $nodeId;
    }

}
