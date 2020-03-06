<?php

namespace Banana\View\Form;

use Cake\Http\ServerRequest as Request;
use Cake\Utility\Hash;
use Cake\View\Form\ContextInterface;

/**
 * Provides a context provider for Content\View\ViewModule instances.
 *
 * This context provider simply fulfils the interface requirements
 * that FormHelper has and allows access to the request data.
 */
class ViewModuleContext implements ContextInterface
{

    /**
     * The request object.
     *
     * @var \Cake\Http\ServerRequest
     */
    protected $_request;

    /**
     * @var \Banana\View\ViewModule;
     */
    protected $_module;

    /**
     * Constructor.
     *
     * @param \Cake\Http\ServerRequest $request The request object.
     * @param array $context Context info.
     */
    public function __construct(Request $request, array $context)
    {
        $this->_request = $request;
        $context += [
            'entity' => null,
        ];
        $this->_module = $context['entity'];
        $this->_module->loadSources();
    }

    /**
     * {@inheritDoc}
     */
    public function getPrimaryKey()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function isPrimaryKey($field)
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isCreate()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function val($field, $options = [])
    {
        $options += [
            'default' => null,
            'schemaDefault' => true
        ];

        $val = $this->_request->data($field);
        if ($val !== null) {
            return $val;
        }

        return $options['default'];
    }

    /**
     * {@inheritDoc}
     */
    public function isRequired($field)
    {
        $validator = $this->_module->validator();
        if (!$validator->hasField($field)) {
            return false;
        }
        if ($this->type($field) !== 'boolean') {
            return $validator->isEmptyAllowed($field, $this->isCreate()) === false;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function fieldNames()
    {
        return $this->_module->schema()->fields();
    }

    /**
     * {@inheritDoc}
     */
    public function type($field)
    {
        return $this->_module->schema()->fieldType($field);
    }

    /**
     * {@inheritDoc}
     */
    public function attributes($field)
    {
        $attrs = (array)$this->_module->schema()->field($field);
        //$whitelist = ['length' => null, 'precision' => null];
        //$attrs = array_intersect_key($attrs, $whitelist);
        return $attrs;
    }

    /**
     * {@inheritDoc}
     */
    public function hasError($field)
    {
        $errors = $this->error($field);

        return count($errors) > 0;
    }

    /**
     * {@inheritDoc}
     */
    public function error($field)
    {
        return array_values((array)Hash::get($this->_module->getErrors(), $field, []));
    }

    public function options($field)
    {
        $options = $this->_module->schema()->options($field);

        return $options;
    }
}
