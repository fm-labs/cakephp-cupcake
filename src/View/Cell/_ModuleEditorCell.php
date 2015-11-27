<?php

namespace Banana\View\Cell;

use Banana\Form\ModuleParamsForm;
use Banana\Model\Entity\Page;
use Banana\View\ViewModule;
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
class ModuleEditorCell extends Cell
{
    public function get($moduleId = null)
    {
        $this->loadModel("Banana.Modules");
        $module = $this->Modules->get($moduleId);

        $this->template = 'display';
        $this->display($module);
    }

    public function display($module = null, $page = null)
    {
        if (!$module || !($module instanceof Module)) {
            throw new \LogicException("ModuleCell did not receive a valid module entity");
        }

        //if ($page && !($page instanceof Page)) {
        //    throw new \LogicException("ModuleCell did not receive a valid page entity");
        //}

        $modulePath = $module->path;
        $class = ViewModule::className($modulePath);
        if (!$class || !class_exists($class)) {
            throw new Exception(sprintf("Module class '%s' not found", $class));
        }

        $moduleClass = $class;
        $moduleSchema = $class::schema();
        $moduleFormInputs = $class::inputs();
        $moduleParamsJson = $module->params;
        $moduleParams = ($moduleParamsJson) ? json_decode($moduleParamsJson, true) : [];
        $moduleFormUrl = ['action' => 'edit_module', $module->id];

        $form = new ModuleParamsForm();
        $form->schema($moduleSchema);

        $this->set('moduleClass', $moduleClass);
        $this->set('modulePath', $modulePath);
        $this->set('moduleParamsJson', $moduleParamsJson);
        $this->set('moduleParams', $moduleParams);
        $this->set('moduleSchema', $moduleSchema);
        $this->set('moduleForm', $form);
        $this->set('moduleFormUrl', $moduleFormUrl);
        $this->set('moduleFormInputs', $moduleFormInputs);
        $this->set('module', $module);
    }
}
