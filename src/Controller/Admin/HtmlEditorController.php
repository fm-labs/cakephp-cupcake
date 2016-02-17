<?php
namespace Banana\Controller\Admin;


use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Routing\Router;
use Media\Lib\Media\MediaManager;

class HtmlEditorController extends AppController
{
    public function imageList($media = 'images')
    {
        $this->viewBuilder()->className('Json');

        $list = [];
        try {
            $mm = MediaManager::get($media);
            $files = $mm->getSelectListRecursive();

            array_walk($files, function($filename, $idx) use (&$list, &$mm) {
                $list[] = [
                    'title' => $idx,
                    'value' => $filename
                ];
            });
        } catch (\Exception $ex) {
            Log::critical('HtmlEditor::imageList ' . $ex->getMessage(), ['banana']);
        }
        $this->set('list', $list);
        $this->set('_serialize', 'list');
    }

    public function linkList()
    {
        $this->viewBuilder()->className('Json');

        $list = [];

        $this->eventManager()->on('Banana.HtmlEditor.buildLinkList', function($event) {

            $_list = [];
            try {
                $this->loadModel('Banana.Pages');
                $result = $this->Pages->find()->contain([])->all()->toArray();

                array_walk($result, function($entity) use (&$_list) {
                    $_list[] = [
                        'title' => str_repeat('_', $entity->level) . $entity->title,
                        //'value' => Router::url($entity->url, true)
                        'value' => sprintf('{{Content.Pages:%s}}', $entity->id)
                    ];
                });

            } catch (\Exception $ex) {
                Log::critical('HtmlEditor::linkList ' . $ex->getMessage(), ['banana']);
            }

            $event->data['list'][] = ['title' => __('Pages'), 'menu' => $_list];
        });

        $this->eventManager()->on('Banana.HtmlEditor.buildLinkList', function($event) {

            $_list = [];
            try {
                $this->loadModel('Banana.Posts');
                $result = $this->Pages->Posts
                    ->find()
                    ->where(['refscope' => 'Banana.Pages'])
                    ->order(['title' => 'ASC'])
                    //->select(['id', 'title', 'level'])
                    ->contain([])
                    ->all()
                    ->toArray();

                array_walk($result, function($entity) use (&$_list) {
                    $_list[] = [
                        'title' => str_repeat('_', $entity->level) . $entity->title,
                        //'value' => Router::url($entity->url, true)
                        'value' => sprintf('{{Content.Posts:%s}}', $entity->id)
                    ];
                });

            } catch (\Exception $ex) {
                Log::critical('HtmlEditor::linkList ' . $ex->getMessage(), ['banana']);
            }

            $event->data['list'][] = ['title' => __('Posts'), 'menu' => $_list];
        });

        if (Plugin::loaded('Shop')):

            $this->eventManager()->on('Banana.HtmlEditor.buildLinkList', function($event) {

                $_list = [];
                try {
                    $this->loadModel('Shop.ShopCategories');
                    $result = $this->ShopCategories->find()->contain([])->all()->toArray();

                    array_walk($result, function($entity) use (&$_list) {
                        $_list[] = [
                            'title' => str_repeat('_', $entity->level) . $entity->name,
                            //'value' => Router::url($entity->url, true)
                            'value' => sprintf('{{Shop.ShopCategories:%s}}', $entity->id)
                        ];
                    });

                } catch (\Exception $ex) {
                    Log::critical('HtmlEditor::linkList ' . $ex->getMessage(), ['banana']);
                }

                $event->data['list'][] = ['title' => __('Shop Categories'), 'menu' => $_list];
            });

        endif;

        $event = $this->dispatchEvent('Banana.HtmlEditor.buildLinkList', ['list' => $list], $this);


        $this->set('list', $event->data['list']);
        $this->set('_serialize', 'list');
    }
}