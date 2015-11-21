<?php
namespace Banana\Controller;


class ModulesController extends AppController
{
    public function view($id = null)
    {
        if ($this->request->query('iframe') === true) {
            $this->viewBuilder()->layout("Banana.iframe/module");
        }

        $module = $this->Modules->get($id, [
            'contain' => []
        ]);
        $module = $this->Modules->modularize($module);
        $this->set('module', $module);
        $this->set('_serialize', ['module']);
    }
}