<?php
namespace Banana\Controller;

use Cake\Core\Configure;
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
        $this->viewBuilder()->plugin('Banana');
        $this->viewBuilder()->theme(Configure::read('Site.theme'));
        $this->viewBuilder()->layout('error');
        $this->viewBuilder()->templatePath('Error');
    }
}
