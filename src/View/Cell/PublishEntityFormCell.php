<?php
declare(strict_types=1);

namespace Banana\View\Cell;

use Cake\View\Cell;

class PublishEntityFormCell extends Cell
{
    public function display($params = [])
    {
        $params += ['modelClass' => null, 'entity' => null];
        $this->set($params);
    }
}
