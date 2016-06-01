<?php

namespace Banana\Page;

use Banana\Model\Entity\Page;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

abstract class AbstractPageType
{

    public function getUrl(EntityInterface $page)
    {
        if (Configure::read('Banana.Router.enablePrettyUrls')) {

            $pageUrl = [
                'prefix' => false,
                'plugin' => 'Banana',
                'controller' => 'Pages',
                'action' => 'view',
                'page_id' => $page->id,
                'slug' => $page->slug,
            ];
        } else {

            $pageUrl = [
                'prefix' => false,
                'plugin' => 'Banana',
                'controller' => 'Pages',
                'action' => 'view',
                $page->id,
                'slug' => $page->slug,
            ];
        }

        return $pageUrl;
    }

    public function getAdminUrl(EntityInterface $page)
    {
        return [
            'prefix' => 'admin',
            'plugin' => 'Banana',
            'controller' => 'Pages',
            'action' => 'manage',
            $page->id,
        ];
    }

    public function getChildren(EntityInterface $page)
    {
        return TableRegistry::get('Banana.Pages')
            ->find()
            ->where(['parent_id' => $page->id])
            ->orderAsc('lft')
            ->all()
            ->toArray();
    }

}