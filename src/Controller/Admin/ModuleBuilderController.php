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


    public function build()
    {
        $class = $this->request->query('class');

        $className = App::className($class, 'View/Module', 'Module');
        if (!$class || !class_exists($className)) {
            throw new Exception(sprintf("Module '%s' not found", $className));
        }

        $module = new $className();

        if ($this->request->is('post') || $this->request->is('put')) {

        }

        $this->set('className', $className);
        $this->set('module', $module);
    }

    public function view()
    {
        $class = $this->request->query('class');

        $this->Modules->entityClass($class);
        $module = $this->Modules->newEntity();

        if ($this->request->is('post') || $this->request->is('put')) {
            $module->config($this->request->data());
        }
        $this->set('module', $module);
        $this->set('class', $class);
        $this->set('data', $this->request->data());
    }
}
