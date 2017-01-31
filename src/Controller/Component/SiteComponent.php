<?php

namespace Banana\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class SiteComponent extends Component
{
    const SESSION_KEY = 'Site';

    protected $_site;

    public function initialize(array $config)
    {
        if (!$this->request->session()->read(self::SESSION_KEY)) {

            $siteHost = env('HTTP_HOST');
            if (defined('BANANA_HOST')) {
                $siteHost = constant('BANANA_HOST');
            }

            $site = TableRegistry::get('Banana.Sites')->find()->where(['Sites.hostname' => $siteHost])->first();
            $this->_site = ($site) ? $site->toArray() : null;
            $this->request->session()->write(self::SESSION_KEY, $this->_site);
        } else {
            //$site = TableRegistry::get('Banana.Sites')->newEntity($this->request->session()->read(self::SESSION_KEY));
            //$this->_site = $site->toArray();
            $this->_site = $this->request->session()->read(self::SESSION_KEY);
        }
    }

    public function getSiteId()
    {
        return ($this->_site) ? $this->_site['id'] : null;
    }

    public function getSite()
    {
        return $this->_site;
    }

    public function shutdown(Event $event)
    {
    }

    public function beforeRender(Event $event) {
        $this->_registry->getController()->set([
            '_banana_site' => $this->_site
        ]);
    }
}