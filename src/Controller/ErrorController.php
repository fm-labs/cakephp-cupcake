<?php

namespace Banana\Controller;


use Cake\Controller\Controller;
use Cake\Event\Event;

class ErrorController extends Controller
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
        $this->viewBuilder()->templatePath('Error');
    }
}