<?php

namespace Banana\Page;

use Banana\Model\Entity\Page;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

abstract class AbstractPageType
{
    protected $page;
    
    public function __construct(Page $page)
    {
        $this->page =& $page;
    }
    
    public function getUrl()
    {
        if (Configure::read('Banana.Router.enablePrettyUrls')) {

            $pageUrl = [
                'prefix' => false,
                'plugin' => 'Banana',
                'controller' => 'Pages',
                'action' => 'view',
                'page_id' => $this->page->id,
                'slug' => $this->page->slug,
            ];
        } else {

            $pageUrl = [
                'prefix' => false,
                'plugin' => 'Banana',
                'controller' => 'Pages',
                'action' => 'view',
                $this->page->id,
                'slug' => $this->page->slug,
            ];
        }

        return $pageUrl;
    }

    public function getAdminUrl()
    {
        return [
            'prefix' => 'admin',
            'plugin' => 'Banana',
            'controller' => 'Pages',
            'action' => 'manage',
            $this->page->id,
        ];
    }

    public function getChildren()
    {
        return TableRegistry::get('Banana.Pages')
            ->find()
            ->where(['parent_id' => $this->page->id])
            ->orderAsc('lft')
            ->all()
            ->toArray();
    }

    public function isPublished()
    {
        return $this->page->is_published;
    }

    public function isHiddenInNav()
    {
        return $this->page->hide_in_nav;
    }
}