<?php

namespace Banana\Controller\Admin;


use Content\Setup\ContentSetup;

class SetupController extends AppController
{
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