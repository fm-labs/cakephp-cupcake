<?php
namespace Banana\Controller\Admin;


use Cake\Core\Plugin;
use Cake\Event\Event;
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

        $this->eventManager()->on('Banana.attachLinkList', function($event) {

            //$event->data['list'][] = ['title' => '-- PAGES --', 'value' => ''];
            try {
                $this->loadModel('Banana.Pages');
                $result = $this->Pages->find()->contain([])->all()->toArray();

                array_walk($result, function($entity) use (&$event) {
                    $event->data['list'][] = ['title' => str_repeat('_', $entity->level) . $entity->title, 'value' => Router::url($entity->url, true)];
                });

            } catch (\Exception $ex) {
                Log::critical('DataController::editorImageList: ' . $ex->getMessage());
            }

        });

        /*
        if (Plugin::loaded('Shop')):

            $this->eventManager()->on('Banana.attachLinkList', function($event) {

                //$event->data['list'][] = ['title' => '-- PAGES --', 'value' => ''];
                try {
                    $this->loadModel('Shop.ShopCategories');
                    $result = $this->ShopCategories->find()->contain([])->all()->toArray();

                    array_walk($result, function($entity) use (&$event) {
                        $event->data['list'][] = ['title' => str_repeat('_', $entity->level) . $entity->name, 'value' => Router::url($entity->url, true)];
                    });

                } catch (\Exception $ex) {
                    Log::critical('DataController::editorImageList: ' . $ex->getMessage());
                }
            });

        endif;
        */

        $event = $this->dispatchEvent('Banana.attachLinkList', ['list' => $list], $this);


        $this->set('list', $event->data['list']);
        $this->set('_serialize', 'list');
    }
}