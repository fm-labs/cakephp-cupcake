<?php

namespace Banana\View\Cell;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\View\Cell;

class MediaEntityFormCell extends Cell
{
    public function display($params = [])
    {
        $params += ['modelClass' => null, 'entity' => null];
        $fields = [];

        if ($params['modelClass']) {
            $Model = TableRegistry::get($params['modelClass']);
            if ($Model->hasBehavior('Media')) {
                $fields = $Model->getMediaFields();
            }
        }

        $this->set('fields', $fields);
        $this->set($params);
    }
}
