<?php

namespace Banana\View\Cell;

use Banana\Form\ModuleParamsForm;
use Banana\Model\Entity\Page;
use Banana\View\ViewModule;
use Cake\Core\App;
use Cake\Core\Exception\Exception;
use Cake\View\Cell;

use Banana\Model\Entity\Module;
use Banana\Model\Table\ModulesTable;

/**
 * Class ModuleCell
 * @package App\View\Cell
 *
 * @property ModulesTable $Modules
 */
class ModuleBuilderCell extends Cell
{


    public function display($module = null, $page = null)
    {
    }

    public function form($module = null)
    {
        debug($module);

        $class = $module->path;
        $className = App::className($class, 'View/Cell', 'ModuleCell');

        $form = new ModuleParamsForm();
        $formInputs = $className::inputs();

        if ($this->request->is('post') || $this->request->is('put')) {
            $module->accessible(array_keys($className::defaults()));
            $module->accessible('_save', true);
            $module = $this->Modules->patchEntity($module, $this->request->data());
            if ($module->_save == true && $module = $this->Modules->save($module)) {
                $this->Flash->success(__('Module has been saved with ID {0}', $module->id));
            } elseif ($module->_save == true) {
                debug($module->errors());
            }
        }

        $this->set('module', $module);
        $this->set('form', $form);
        $this->set('inputs', $formInputs);
    }
}
