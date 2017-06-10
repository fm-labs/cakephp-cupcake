<?php

namespace Banana\Controller\Admin;

use Content\Setup\ContentSetup;

/**
 * Class SetupController
 *
 * @package Banana\Controller\Admin
 *
 * @TODO !Experimental!
 */
class SetupController extends AppController
{
    /**
     * @return \Cake\Network\Response|null
     * @throws \Exception
     */
    public function activate()
    {
        $this->autoRender = false;

        $setup = new ContentSetup();
        $setup->activate();

        $this->response->type('text');
        $this->response->body('Hello');

        return $this->response;
    }
}
