<?php
namespace Banana\View;

use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\View\View;
use Cake\View\Exception;

/**
 * Provides module() method for usage in Controller and View classes.
 *
 */
trait ViewModuleTrait
{

    /**
     * Renders the given module.
     *
     * Modules behaves exactly like cell, except of a slightly different template structure
     *
     * @param string $module You must indicate cell name, and optionally a cell action. e.g.: `TagCloud::smallList`
     * will invoke `View\Module\TagCloudModule::smallList()`, `display` action will be invoked by default when none is provided.
     * @param array $args Additional arguments for cell method. e.g.:
     *    `module('TagCloud::smallList', ['a1' => 'v1', 'a2' => 'v2'])` maps to `View\Module\TagCloud::smallList(['a1' => 'v1', 'a2' => 'v2'])`
     * @param array $options Options for Module's constructor
     * @return \Banana\View\ViewModule The module instance
     * @throws \Cake\View\Exception\MissingCellException If Module class was not found.
     * @throws \BadMethodCallException If Module class does not specified cell action.
     */
    public function module($module, array $args = [], array $options = [])
    {
        try {
            $moduleEntityClass = "\\Content\\Model\\Entity\\Module";
            if (class_exists($moduleEntityClass) && $module instanceof $moduleEntityClass) {
                $options = $module->params_arr;
                $module = $module->cellClass;
            }

            $parts = explode('::', $module);

            if (count($parts) === 2) {
                list($pluginAndModule, $action) = [$parts[0], $parts[1]];
            } else {
                list($pluginAndModule, $action) = [$parts[0], 'display'];
            }

            list($plugin) = pluginSplit($pluginAndModule);

            if (!Plugin::loaded($plugin)) {
                throw new MissingPluginException(['plugin' => $plugin]);
            }

            $className = App::className($pluginAndModule, 'View/Module', 'Module');

            if (!$className) {
                //@todo MissingModuleException
                throw new Exception\MissingCellException(['className' => $pluginAndModule . 'Module']);
            }

            $module = $this->_createModule($className, $action, $plugin, $options);
            $module->args = $args;

            /*
            try {
                $reflect = new \ReflectionMethod($module, $action);
                $reflect->invokeArgs($module, $args); // ViewModule::display(array $args = [])
                return $module;
            } catch (\ReflectionException $e) {
                throw new \BadMethodCallException(sprintf(
                    'Module %s does not have a "%s" method.',
                    $className,
                    $action
                ));
            }
            */
            return $module;
        } catch (\Exception $ex) {
            return sprintf("Module $module error: %s", $ex->getMessage());
        }
    }

    /**
     * Create module instance.
     *
     * @param string $className The module classname.
     * @param string $action The action name.
     * @param string $plugin The plugin name.
     * @param array $options The constructor options for the module.
     * @return \Banana\View\ViewModule;
     */
    protected function _createModule($className, $action, $plugin, $options)
    {
        if ($this instanceof View || $this instanceof Controller) {
            $instance = new $className($this, $this->request, $this->response, $this->getEventManager(), $options);
            $instance->plugin = $plugin;
            $instance->action = $action;

            return $instance;
        }

        throw new \InvalidArgumentException('ModuleTrait MUST have View or Controller class as superclass');
    }
}
