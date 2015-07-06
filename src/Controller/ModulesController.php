<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/22/15
 * Time: 4:07 PM
 */

namespace App\Controller;


class ModulesController extends AppController
{
    public function view($id = null)
    {
        $this->layout = "iframe/module";

        $module = $this->Modules->get($id, [
            'contain' => []
        ]);
        $this->set('module', $module);
        $this->set('_serialize', ['module']);
    }
}