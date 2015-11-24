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


class FlexSliderCell extends Cell
{
    public $modelClass = "Banana.Pages";

    protected $params = [
        'source' => 'folder', // folder|posts
    ];

    public function display($params = [])
    {
        $params = array_merge($this->params, $params);
    }
}
