<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 11/18/15
 * Time: 9:52 PM
 */

namespace Banana\Model\Entity\Module;

use Banana\Model\Entity\Module;
use Cake\Core\Exception\Exception;
use Cake\Core\InstanceConfigTrait;
use Cake\ORM\Entity;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

abstract class BaseModule extends Module
{

    protected $_defaultParams = [];

    public function __construct(array $properties = [], array $options = [])
    {
        $this->accessible('params_arr', false);
        //$this->_properties['params_arr'] = [];

        $this->accessible(array_keys($this->_defaultParams), true);
        $this->setParams($this->_defaultParams);

        parent::__construct($properties, $options);

    }

    public function setParams(array $params, $merge = true)
    {
        if (!isset($this->_properties['params_arr'])) {
            $this->_properties['params_arr'] = $this->_defaultParams;
        }

        if ($merge) {
            $this->_properties['params_arr'] = array_merge($this->_properties['params_arr'], $params);
        } else {
            $this->_properties['params_arr'] = $params;
        }

        foreach ($this->_properties['params_arr'] as $param => $val) {
            $this->set($param, $val);
        }

        $this->_properties['params'] = json_encode($this->_properties['params_arr']);
    }


    protected function _setParams($params = null)
    {
        $this->setParams((array) json_decode($params, true));
    }

    protected function _getParams()
    {
        if (!$this->_properties['params'] && $this->_properties['params_arr']) {
            $this->_properties['params'] = json_encode($this->_properties['params_arr']);
        }
        return $this->_properties['params'];
    }


    protected function _setParamsArr($params)
    {
        $this->setParams($params);
    }


    public function set($property, $value = null, array $options = [])
    {
        parent::set($property, $value, $options);


        $dirtyParam = false;
        foreach (array_keys($this->_defaultParams) as $param)
        {
            if (!$this->has($param)) {
                $this->_properties[$param] = $this->_properties['params_arr'][$param];
            }
            elseif ($this->dirty($param)) {
                $dirtyParam = true;
                $this->_properties['params_arr'][$param] = $this->get($param);
            }
        }

        if ($dirtyParam) {
            $this->_properties['params'] = json_encode($this->params_arr);
        }

    }

    protected function _getParamsArr()
    {
        return $this->_properties['params_arr'];
    }

    protected function _getViewPath()
    {
        throw new InvalidConfigurationException("No view path has been defined for module " . get_called_class() . "'");
    }

    protected function _getFormElement()
    {
        return $this->_getViewPath() . '/form';
    }

    protected function _getFormData()
    {
        return [
            'module' => $this
        ];
    }

    protected function _getFormOptions()
    {
        return [];
    }

    protected function _getViewElement()
    {
        $template = ($this->template) ?: 'display';
        return $this->_getViewPath() . '/' . $template;
    }

    protected function _getViewData()
    {
        return [
            'module' => $this
        ];
    }

    protected function _getViewOptions()
    {
        return [];
    }


    abstract public function processForm(Entity $entity, array $formData);

}