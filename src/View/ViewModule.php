<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/19/15
 * Time: 9:50 PM
 */

namespace Banana\View;

use Cake\Core\App;
use Cake\Core\Exception\Exception;
use Cake\Event\EventManager;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\View\Cell;
use Cake\View\Exception\MissingTemplateException;
use Cake\View\Exception\MissingCellViewException;
use Cake\View\View;
use Cake\Utility\Inflector;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * Class ViewModule
 *
 * Cells on steroids
 *
 * @package Banana\View
 * @property View $View
 */
abstract class ViewModule extends Cell
{
    /**
     * @var string Subdirectory prefix with trailing slash eg. Core/
     */
    protected $subDir = "";

    /**
     * @var array Module parameters
     */
    protected $params = [];

    /**
     * Constructor.
     *
     * @param \Cake\Network\Request $request The request to use in the cell.
     * @param \Cake\Network\Response $response The response to use in the cell.
     * @param \Cake\Event\EventManager $eventManager The eventManager to bind events to.
     * @param array $cellOptions Cell options to apply.
     */
    public function __construct(
        Request $request = null,
        Response $response = null,
        EventManager $eventManager = null,
        array $cellOptions = []
    ) {
        // extract params from cellOptions, if any
        $params = [];
        if (isset($cellOptions['params'])) {
            $params = $cellOptions['params'];
            unset($cellOptions['params']);
        }

        // set params (and automatically set params as view vars)
        $this->setParams($params);

        parent::__construct($request, $response, $eventManager, $cellOptions);
    }

    /**
     * Merge or replace widget param.
     * Params will be automatically available as view vars.
     *
     * @param array $params
     * @param bool $merge
     * @throws \InvalidArgumentException
     */
    protected function setParams(array $params = [], $merge = true)
    {
        if (!is_array($params)) {
            throw new \InvalidArgumentException("Invalid parameter format. ARRAY expected");
        }
        if ($merge) {
            $this->params = array_merge($this->params, $params);
        } else {
            $this->params = $params;
        }

        // set as view vars
        $this->set($this->params);
    }

    abstract public function display($params = []);

    /**
     * Render the cell.
     *
     * @param string|null $template Custom template name to render. If not provided (null), the last
     * value will be used. This value is automatically set by `CellTrait::cell()`.
     * @return string The rendered cell.
     * @throws \Cake\View\Exception\MissingCellViewException When a MissingTemplateException is raised during rendering.
     */
    public function render($template = null)
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
            list($plugin, $template) = pluginSplit($template);

            $className = explode('\\', get_class($this));
            $className = array_pop($className);
            $name = substr($className, 0, strrpos($className, 'Module')); // extract widget name
            $this->View->subDir = 'Module' . DS . $this->subDir . $name; // apply sub dir
            //debug($template . " - " . $className . " - " . $name . " - " . $this->View->subDir);

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

    /**
     * Build widget class name from path
     *
     * e.g. path 'Core/Html' resolves to class name 'View/Module/Core/HtmlModule'
     *
     * @param $path
     * @return bool|string
     */
    public static function className($path)
    {
        return App::className($path, 'View/Module', 'Module');
    }

    /**
     * Returns widget schema
     *
     * Defaults to an empty fallback schema.
     * OVERRIDE IN SUBCLASSES!
     *
     * @return \Cake\Form\Schema
     */
    public static function schema()
    {
        return new Schema();
    }

    /**
     * Returns widget validator
     *
     * Defaults to an empty fallback validator.
     * OVERRIDE IN SUBCLASSES!
     *
     * @return \Cake\Validation\Validator
     */
    public static function validator()
    {
        return new Validator();
    }

    /**
     * Returns widget form inputs customization
     *
     * @return array
     */
    public static function inputs()
    {
        return [];
    }
}
