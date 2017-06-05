<?php
namespace Banana\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Class ErrorController
 *
 * @package Banana\Controller
 */
class ErrorController extends \Cake\Controller\ErrorController
{

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('RequestHandler');
    }

    /**
     * beforeRender callback.
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        //$this->viewBuilder()->plugin('Banana');
        //$this->viewBuilder()->layout('test_error');
        $this->viewBuilder()->templatePath('Error');
    }
}
