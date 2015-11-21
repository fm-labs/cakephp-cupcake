<?php
namespace Banana\Controller\Admin;

use Banana\Form\ModuleParamsForm;
use Banana\View\ViewModule;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Core\Plugin;
use Banana\Model\Table\ModulesTable;
use Cake\Filesystem\Folder;
use Banana\Model\Entity\Module;
use Cake\Network\Exception\NotFoundException;

/**
 * Class ModuleBuilderController
 * @package App\Controller\Admin
 *
 * @property ModulesTable $Modules
 */
class ModuleBuilderController extends AppController
{
    public $modelClass = "Banana.Modules";

    public function index()
    {
        $this->set('modulesAvailable', $this->getModulesAvailable());
    }

    public function create()
    {
        $modulePath = $this->request->query('path');
        $moduleParams = [];

        $class = ViewModule::className($modulePath);
        if (!$class || !class_exists($class)) {
            throw new Exception(sprintf("Module '%s' not found", $modulePath));
        }

        $moduleSchema = $class::schema();
        $moduleFormInputs = $class::inputs();

        $form = new ModuleParamsForm();
        $form->schema($moduleSchema);

        //$this->loadModel('Modules');
        $module = $this->Modules->newEntity();

        if ($this->request->is('post')) {
            //debug($this->request->data);
            if ($form->execute($this->request->data)) {
                $moduleParams = $this->request->data();
                $this->Flash->success('We will get back to you soon.');
            } else {
                $this->Flash->error('There was a problem submitting your form.');
            }
        } else {
            $this->request->data = $moduleParams;
        }

        $this->set('modulePath', $modulePath);
        $this->set('moduleParams', $moduleParams);
        $this->set('moduleSchema', $moduleSchema);
        $this->set('moduleForm', $form);
        $this->set('moduleFormInputs', $moduleFormInputs);

        $this->set('module', $module);
    }

    public function preview()
    {
        $path = $this->request->query('path');
        $paramsBase64 = $this->request->query('params');

        if (!$path) {
            throw new \InvalidArgumentException("Module path not set");
        }

        if (!$paramsBase64) {
            throw new \InvalidArgumentException("Module params not set");
        }

        $params = base64_decode($paramsBase64);
        $params = json_decode($params, true);

        $this->layout = "iframe/module";

        $this->set('modulePath', $path);
        $this->set('moduleParams', $params);
    }



    public function build($id = null)
    {
        if (!$id) {
            $class = $this->request->query('class');
            $className = App::className($class, 'Model/Entity/Module', 'Module');

            $this->Modules->entityClass($className);
            $module = $this->Modules->newEntity();
            $module->path = $class;
        } else {
            $module = $this->Modules->get($id);
            $module = $this->Modules->modularize($module);
            $class = $module->path;
            $className = get_class($module);
        }

        if (!$module) {
            throw new NotFoundException('Module not found');
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $module->accessible('_save', true);
            $module = $this->Modules->patchEntity($module, $this->request->data());
            if ($module->_save == true && $module = $this->Modules->save($module)) {
                $this->Flash->success(__('Module has been saved with ID {0}', $module->id));
            } elseif ($module->_save == true) {
                debug($module->errors());
            }
        }
        $this->set('module', $module);
        $this->set('class', $class);
        $this->set('className', $className);
        $this->set('data', $this->request->data());
    }

    public function edit($id = null)
    {
        $this->setAction('build', $id);
    }
}
