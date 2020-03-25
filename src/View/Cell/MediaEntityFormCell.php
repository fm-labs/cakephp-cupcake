<?php
declare(strict_types=1);

namespace Banana\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\View\Cell;

class MediaEntityFormCell extends Cell
{
    public function display($params = [])
    {
        $params += ['modelClass' => null, 'entity' => null];
        $fields = [];

        if ($params['modelClass']) {
            $Model = TableRegistry::getTableLocator()->get($params['modelClass']);
            if ($Model->hasBehavior('Media')) {
                $fields = $Model->getMediaFields();
            }
        }

        $this->set('fields', $fields);
        $this->set($params);
    }
}
