<?php
namespace Banana\View\Cell;

use Cake\Event\EventManager;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\View\Cell;

abstract class ModuleCell extends Cell
{
    public static $defaultParams = [
    ];

    protected $_validCellOptions = ['module', 'params', 'section', 'page_id'];

    public $section;

    public $page_id;

    public $module;

    public $params;
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

        parent::__construct($request, $response, $eventManager, $cellOptions);

        $this->params = ($this->module)
            ? array_merge(static::$defaultParams, $this->params, $this->module->params_arr)
            : array_merge(static::$defaultParams, $this->params);
    }

    public function display()
    {
        $this->set('params', $this->params);
        //$this->set('module', $this->module);
    }

    public static function defaults()
    {
        return static::$defaultParams;
    }

    public static function inputs()
    {
        $inputs = [];
        array_walk(static::$defaultParams, function ($val, $idx) use (&$inputs) {
            $inputs[$idx] = ['default' => $val];
        });
        return $inputs;
    }
}