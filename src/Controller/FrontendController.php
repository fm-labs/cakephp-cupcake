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
    public $components = ['Banana.Frontend'];

    public $viewClass = 'Banana.Frontend';

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Flash');
        $this->viewClass = $this->_getViewClass();
        $this->theme = $this->_getTheme();
        $this->layout = $this->_getLayout();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    protected function _getTheme()
    {
        return Configure::read('Banana.frontend.theme');
    }

    protected function _getViewClass()
    {
        return 'Banana.Frontend';
    }

    protected function _getLayout()
    {
        return 'frontend';
    }
} 