<?php
namespace Banana\Controller\Admin;


use Cake\Log\Log;
use Cake\Routing\Router;
use Media\Lib\Media\MediaManager;

class DataController extends AppController
{
    public function editorImageList($media = 'images')
    {
        $this->viewClass = 'Json';

        $list = [];
        try {
            $mm = MediaManager::get($media);
            $files = $mm->getSelectListRecursive();

            array_walk($files, function($filename, $idx) use (&$list, &$mm) {
                $list[] = ['title' => $idx, 'value' => $filename];
            });
        } catch (\Exception $ex) {
            Log::critical('DataController::editorImageList: ' . $ex->getMessage());
        }
        $this->set('list', $list);
        $this->set('_serialize', 'list');
    }

    public function editorLinkList()
    {
        $this->viewClass = 'Json';

        $list = [];
        try {
            $this->loadModel('Banana.Pages');
            $pages = $this->Pages->find()->contain([])->all()->toArray();

            array_walk($pages, function($page) use (&$list) {
                $list[] = ['title' => $page->title, 'value' => Router::url($page->url, true)];
            });

        } catch (\Exception $ex) {
            Log::critical('DataController::editorImageList: ' . $ex->getMessage());
        }

        $this->set('list', $list);
        $this->set('_serialize', 'list');
    }
}