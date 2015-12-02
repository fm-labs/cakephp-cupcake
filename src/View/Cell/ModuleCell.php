<?php
namespace Banana\View\Cell;

use Cake\View\Cell;

abstract class ModuleCell extends Cell
{
    public static $defaultParams = [
    ];

    public function display($module = null)
    {
        $params = array_merge(static::$defaultParams, $module->params_arr);
        $this->set('params', $params);
        $this->set('module', $module);
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