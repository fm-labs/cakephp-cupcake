<?php

namespace Banana\View;

use BadMethodCallException;
use Cake\Cache\Cache;
use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Event\EventManager;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\View\Cell;
use Cake\View\Exception\MissingTemplateException;
use Cake\View\Exception\MissingCellViewException;
use Cake\View\View;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use ReflectionException;
use ReflectionMethod;

/**
 * Class ViewModule
 *
 * Cells on steroids
 *
 * @package Content\View
 * @property View $View
 */
abstract class ViewModule extends Cell
{
    /**
     * The schema used by this form.
     *
     * @var \Banana\View\ViewModuleSchema;
     */
    protected $_schema;

    /**
     * The errors if any
     *
     * @var array
     */
    protected $_errors = [];

    /**
     * The validator used by this form.
     *
     * @var \Cake\Validation\Validator;
     */
    protected $_validator;

    /**
     * @var View
     */
    protected $_View;

    /**
     * @var Controller
     */
    protected $_Controller;

    /**
     * Constructor.
     * @param View|Controller|null $parent Parent View or Controller instance
     * @param Request $request
     * @param Response $response
     * @param EventManager $eventManager
     * @param array $cellOptions Cell options to apply.
     */
    public function __construct(
        &$parent,
        Request $request,
        Response $response,
        EventManager $eventManager,
        array $cellOptions = []
    ) {
        parent::__construct($request, $response, $eventManager, $cellOptions);

        if ($parent instanceof View) {
            $this->_View = $parent;
        }
        elseif ($parent instanceof View) {
            $this->_Controller = $parent;
        }
    }

    //abstract public function display();


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
        $cache = [];
        if ($this->_cache) {
            $cache = $this->_cacheConfig($this->action, $template);
        }

        $render = function () use ($template) {
            try {
                $reflect = new ReflectionMethod($this, $this->action);
                $reflect->invokeArgs($this, $this->args);
            } catch (ReflectionException $e) {
                throw new BadMethodCallException(sprintf(
                    'Class %s does not have a "%s" method.',
                    get_class($this),
                    $this->action
                ));
            }

            $builder = $this->viewBuilder();

            if ($template !== null &&
                strpos($template, '/') === false &&
                strpos($template, '.') === false
            ) {
                $template = Inflector::underscore($template);
            }
            if ($template === null) {
                $template = $builder->template() ?: $this->template;
            }
            if ($template === null) {
                $template = $this->action;
            }
            $builder->layout(false)
                ->template($template);

            $className = get_class($this);
            $namePrefix = '\View\Module\\';
            $name = substr($className, strpos($className, $namePrefix) + strlen($namePrefix));
            $name = substr($name, 0, -6);
            if (!$builder->templatePath()) {
                //debug('Module' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $name));
                $builder->templatePath('Module' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $name));
            }

            $this->View = $this->createView();
            try {
                return $this->View->render($template);
            } catch (MissingTemplateException $e) {
                throw new MissingCellViewException(['file' => $template, 'name' => $name]);
            }
        };

        if ($cache) {
            return Cache::remember($cache['key'], $render, $cache['config']);
        }

        return $render();
    }

    public function createView($viewClass = NULL)
    {
        $builder = $this->viewBuilder();

        if ($this->plugin) {
            $builder->plugin($this->plugin);
        }

        if ($this->_View) {
            if (!empty($this->_View->helpers)) {
                $builder->helpers($this->_View->helpers);
            }

            if (!empty($this->_View->theme)) {
                $builder->theme($this->_View->theme);
            }

            $class = get_class($this->_View);
            $builder->className($class);
        }
        elseif ($this->_Controller) {
            $builder->helpers($this->_Controller->viewBuilder()->helpers());
            $builder->theme($this->_Controller->viewBuilder()->theme());
            $builder->className($this->_Controller->viewBuilder()->className());
        }

        return $builder->build(
            $this->viewVars,
            $this->request,
            $this->response,
            null //$this->eventManager()
        );
    }

    /**
     * Get/Set the schema for this form.
     *
     * This method will call `_buildSchema()` when the schema
     * is first built. This hook method lets you configure the
     * schema or load a pre-defined one.
     *
     * @param \Banana\View\ViewModuleSchema|null $schema The schema to set, or null.
     * @return \Banana\View\ViewModuleSchema the schema instance.
     */
    public function schema(ViewModuleSchema $schema = null)
    {
        if ($schema === null && empty($this->_schema)) {
            $schema = $this->_buildSchema(new ViewModuleSchema());
        }
        if ($schema) {
            $this->_schema = $schema;
        }

        return $this->_schema;
    }

    /**
     * A hook method intended to be implemented by subclasses.
     *
     * You can use this method to define the schema using
     * the methods on Content\View\Form\ViewModuleSchema, or loads a pre-defined
     * schema from a concrete class.
     *
     * @param \Banana\View\ViewModuleSchema $schema The schema to customize.
     * @return \Banana\View\ViewModuleSchema The schema to use.
     */
    protected function _buildSchema(ViewModuleSchema $schema)
    {
        return $schema;
    }

    /**
     * Load input sources and inject as view vars.
     * Required for the FormHelper to detect input options
     *
     * @return void
     */
    public function loadSources()
    {
        foreach ($this->schema()->fields() as $field) {
            $options = $this->schema()->options($field);
            if (!$options || !isset($options['options'])) {
                continue;
            }

            $optionsField = $field;
            $optionsField = (substr($optionsField, -3) == '_id') ? substr($optionsField, 0, -3) : $optionsField;

            $optionsField = Inflector::pluralize($optionsField);
            if ($this->_View) {
                $this->_View->set($optionsField, $options['options']);
            }
            if ($this->_Controller) {
                $this->_Controller->set($optionsField, $options['options']);
            }
        }
    }

    /**
     * Get/Set the validator for this form.
     *
     * This method will call `_buildValidator()` when the validator
     * is first built. This hook method lets you configure the
     * validator or load a pre-defined one.
     *
     * @param \Cake\Validation\Validator|null $validator The validator to set, or null.
     * @return \Cake\Validation\Validator the validator instance.
     */
    public function validator(Validator $validator = null)
    {
        if ($validator === null && empty($this->_validator)) {
            $validator = $this->_buildValidator(new Validator());
        }
        if ($validator) {
            $this->_validator = $validator;
        }

        return $this->_validator;
    }

    /**
     * A hook method intended to be implemented by subclasses.
     *
     * You can use this method to define the validator using
     * the methods on Cake\Validation\Validator or loads a pre-defined
     * validator from a concrete class.
     *
     * @param \Cake\Validation\Validator $validator The validator to customize.
     * @return \Cake\Validation\Validator The validator to use.
     */
    protected function _buildValidator(Validator $validator)
    {
        return $validator;
    }

    /**
     * Used to check if $data passes this form's validation.
     *
     * @param array $data The data to check.
     * @return bool Whether or not the data is valid.
     */
    public function validate(array $data)
    {
        $validator = $this->validator();
        $this->_errors = $validator->errors($data);

        return count($this->_errors) === 0;
    }

    /**
     * Get the errors in the form
     *
     * Will return the errors from the last call
     * to `validate()` or `execute()`.
     *
     * @return array Last set validation errors.
     */
    public function errors()
    {
        return $this->_errors;
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

}
