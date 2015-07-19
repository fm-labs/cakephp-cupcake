<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 6/19/15
 * Time: 10:59 PM
 */

namespace Banana\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;

abstract class FrontendController extends AppController
{
    //public $theme = 'Default';

    public $layout = 'frontend';

    public $viewClass = 'Banana.Frontend';

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->loadPage();
        $this->loadTheme();
        $this->loadLayout();
    }

    protected function loadPage($page = null)
    {
        //@TODO load page from id or slug (query params or passed params)
    }

    protected function loadTheme($theme = null)
    {
        //@TODO load theme name from page or config
        $this->theme = Configure::read('Settings.Banana.site.theme');
    }

    protected function loadLayout($layout = null)
    {
        //@TODO load layout name from page or config
    }
} 