<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/24/15
 * Time: 5:27 PM
 */

namespace Banana\View\Cell;

use Cake\View\Cell;
use Banana\Model\Table\PagesTable;

/**
 * Class PageCell
 * @package App\View\Cell
 *
 * @property PagesTable $Pages
 */
class PageCell extends Cell
{
    public $modelClass = "Banana.Pages";

    public function display($pageId = null)
    {
        $this->loadModel("Banana.Pages");

        $page = $this->Pages->find()
            ->where(['Pages.id' => $pageId])
            ->contain(['ContentModules' => ['Modules']])
            ->first();

        $this->set('page', $page);
        $this->set('title', $page->title);
    }
}
