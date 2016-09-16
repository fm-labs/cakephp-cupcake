<?php

namespace Banana\Controller\Admin;

use App\Controller\Admin\AppController as BaseAdminAppController;
use Banana\Core\Banana;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Filesystem\Folder;

class AppController extends BaseAdminAppController
{
    public $paginate = [
        'limit' => 100,
    ];

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $locale = $this->request->query('locale');
        $this->locale = ($locale) ? $locale : Configure::read('Shop.defaultLocale');
        $this->Auth->allow();
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->set('locale', $this->locale);

        //@TODO Move to a CORSComponent
        $this->response->header("Access-Control-Allow-Headers", "DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Authorization");
        $this->response->header("Access-Control-Allow-Origin", "*");
        $this->response->header("Access-Control-Allow-Credentials", "true");
    }

    /**
     * @deprecated
     */
    protected function getModulesAvailable()
    {
        return Banana::getModuleCellsAvailable();
    }

    /**
     * @deprecated
     */
    protected function getModuleTemplatesAvailable()
    {
        return Banana::getModuleCellTemplatesAvailable();
    }

    /**
     * @deprecated
     */
    protected function getLayoutsAvailable()
    {
        return Banana::getLayoutsAvailable();
    }

    /**
     * @deprecated
     */
    protected function getThemesAvailable()
    {
        return Banana::getLayoutsAvailable();
    }
}
