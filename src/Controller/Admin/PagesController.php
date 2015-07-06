<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/25/15
 * Time: 3:02 PM
 */

namespace Banana\Controller\Admin;

use Banana\Model\Table\PagesTable;
use Cake\Event\Event;

/**
 * Class PagesController
 * @package App\Controller\Admin
 *
 * @property PagesTable $Pages
 */
class PagesController extends ContentController
{
    public $modelClass = "Banana.Pages";

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $treeList = $this->model()->find('treeList', [
            /*
            'valuePath' => function ($page) {
                $path = $this->model()->find('path', ['for' => $page->id]);

                $pathStr = "";
                foreach ($path as $part) {
                    $pathStr .= $part->slug . '/';
                }

                return $pathStr;
            },
            */
            'spacer' => '_'
        ]);
        $parentPages = $this->Pages->ParentPages->find('list', ['limit' => 200]);

        $this->set('parentPages', $parentPages);
        $this->set('treeList', $treeList->toArray());
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParentPages']
        ];

        $rootNode = $this->model()->find()->where(['parent_id IS NULL'])->first();
        $children = [];

        if ($rootNode) {
            $children = $this->model()
                ->find('children', ['for' => $rootNode->id])
                ->find('threaded');
        }


        $this->set('contents', $this->paginate($this->model()));
        $this->set('children', $children);
        $this->set('_serialize', ['contents']);
    }
}
