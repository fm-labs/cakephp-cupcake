<?php
declare(strict_types=1);

namespace Banana\Controller;

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
    public function initialize(): void
    {
        $this->loadComponent('RequestHandler');
    }

    /**
     * beforeRender callback.
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        //$this->viewBuilder()->theme(Configure::read('Site.theme'));
        $this->viewBuilder()->setPlugin('Banana');
        $this->viewBuilder()->setLayout('error');
        $this->viewBuilder()->setTemplatePath('Error');
    }
}
