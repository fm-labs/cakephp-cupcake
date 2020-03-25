<?php
declare(strict_types=1);

namespace Banana\View\Cell;

use Cake\Core\Configure;
use Cake\View\Cell;

class TranslateEntityFormCell extends Cell
{
    public function display($params = [])
    {
        $params += ['modelClass' => null, 'entity' => null];
        $this->set($params);

        $this->set('locales', Configure::read('Shop.locales'));
    }
}
