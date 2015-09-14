<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 9/10/15
 * Time: 11:19 PM
 */

namespace Banana\View\Helper;


use Cake\View\View;

class ScriptHelper extends AppHelper
{
    public static $scriptBlockHead = "script";
    public static $scriptBlockBottom = "script-bottom"; // legacy

    public $helpers = ['Html'];

    protected $_defaultConfig = [
        //'autoload_scripts' => ['jquery', 'semanticui'],
        //'autoload_css' => []
    ];

    protected $_scripts = [
        'jquery' => 'Banana.jquery/jquery-1.11.2.min',
        'banana_shared' => 'Banana.shared'

    ];

    protected $_css = [

    ];

    protected $_loaded = ['scripts' => [], 'css' => []];

    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);
    }

    public function loadScript($alias, $path = null)
    {
        if (is_array($alias)) {
            foreach ($alias as $_alias => $_path) {
                $this->loadScript($_alias, $_path);
            }
        } else {
            $this->_scripts[$alias] = $path;
        }
        return $this;
    }

    public function add($name, $options = [], $block = null)
    {
        $this->_addScript($name, $options, $block);
    }

    public function addHead($name, $options = [])
    {
        $this->_addScript($name, $options, static::$scriptBlockHead);
    }

    public function addBottom($name, $options = [])
    {
        $this->_addScript($name, $options, static::$scriptBlockBottom);
    }

    protected function _addScript($name, $options = [], $block = null)
    {
        if (is_string($name) && isset($this->_scripts[$name])) {
            $path = $this->_scripts[$name];
        } else {
            $path = $name;
        }

        if (is_array($path)) {
            foreach ($path as $_path => $nested) {
                if (is_numeric($_path)) {
                    $_path = $nested;
                    $nested = [];
                }

                if (!empty($nested)) {
                    $this->_addScript($nested, $options);
                }

                $this->_addScript($_path, $options);
            }
            return;
        }

        if (isset($this->_loaded['scripts'][$path])) {
            return;
        }

        if ($block === null) {
            $block = static::$scriptBlockHead;
        }
        $options = array_merge(['block' => $block, 'once' => true], $options);

        //debug("Loading script: " . $path . "::" . $options['block']);

        $this->_loaded['scripts'][$path] = true;
        return $this->Html->script($path, $options);
    }
} 