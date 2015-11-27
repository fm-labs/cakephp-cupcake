<?php

namespace Banana\View\Cell;

use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\View\Cell;

use Banana\Model\Entity\Module;
use Banana\Model\Table\ModulesTable;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Event\EventManager;

/**
 * Class ModuleCell
 * @package App\View\Cell
 *
 * @property ModulesTable $Modules
 */
class ModuleRendererCell extends Cell
{
    protected $mergeViewBlocks = [];

    public function __construct(
        Request $request = null,
        Response $response = null,
        EventManager $eventManager = null,
        array $cellOptions = []
    ) {
        parent::__construct($request, $response, $eventManager, $cellOptions);

        //$this->eventManager()->on('View.afterLayout', function (Event $event) {
        //    debug($event->subject()->Blocks->keys());
        //});
    }

    public function get($moduleId = null)
    {
        $this->loadModel("Banana.Modules");
        $module = $this->Modules->get($moduleId);

        $this->template = 'display';
        $this->display($module);
    }

    public function named($moduleName = null)
    {
        $this->loadModel("Banana.Modules");
        $module = $this->Modules->find('first')->where(['name' => $moduleName]);

        $this->template = 'display';
        $this->display($module);
    }

    public function display(Module $module = null, $template = null)
    {
        if (!$module) {
            throw new \LogicException("ModuleCell did not receive a module entity");
        }

        //debug($template);

        $modulePath = $module->path;
        $moduleParams = $module->params;
        $moduleParamsDecoded = json_decode($moduleParams, true);

        list($plugin, $moduleName) = pluginSplit($modulePath);
        $moduleName = join('-', explode('/', $moduleName));
        $moduleName = Inflector::dasherize($moduleName);
        //$moduleName = strtolower($moduleName);
        //$moduleHtmlClass = join(" ", ['module', 'mod-' . $moduleName]);
        $moduleHtmlClass = "module";

        $this->set('module', $module);
        $this->set('moduleParams', $moduleParamsDecoded);
        $this->set('modulePath', $modulePath);
        $this->set('moduleTemplate', $template);
        $this->set('moduleHtmlClass', $moduleHtmlClass);
    }

    /**
     * Render the cell.
     *
     * @param string|null $template Custom template name to render. If not provided (null), the last
     * value will be used. This value is automatically set by `CellTrait::cell()`.
     * @return string The rendered cell.
     * @throws \Cake\View\Exception\MissingCellViewException When a MissingTemplateException is raised during rendering.
     */
    public function __render($template = null)
    {
        if ($template !== null &&
            strpos($template, '/') === false &&
            strpos($template, '.') === false
        ) {
            $template = Inflector::underscore($template);
        }
        if ($template === null) {
            $template = $this->template;
        }
        $this->View = null;
        $this->getView();
        $this->View->layout = false;

        $cache = [];
        if ($this->_cache) {
            $cache = $this->_cacheConfig($template);
        }

        $render = function () use ($template) {
            $className = explode('\\', get_class($this));
            $className = array_pop($className);
            $name = substr($className, 0, strrpos($className, 'Module')); // extract widget name
            $this->View->subDir = 'Module' . DS . $className; // apply sub dir

            debug($this->View->subDir . "--" . $template);
            try {
                return $this->View->render($template);
            } catch (MissingTemplateException $e) {
                throw new MissingCellViewException(['file' => $template, 'name' => $name]);
            }
        };

        if ($cache) {
            return $this->View->cache(function () use ($render) {
                echo $render();
            }, $cache);
        }
        return $render();
    }

}
