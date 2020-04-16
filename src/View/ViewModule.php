<?php
declare(strict_types=1);

namespace Banana\View;

use BadMethodCallException;
use Cake\Cache\Cache;
use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Event\EventManager;
use Cake\Http\Response;
use Cake\Http\ServerRequest as Request;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use Cake\View\Cell;
use Cake\View\Exception\MissingCellViewException;
use Cake\View\Exception\MissingTemplateException;
use Cake\View\View;
use ReflectionException;
use ReflectionMethod;

/**
 * Class ViewModule
 *
 * Cells on steroids
 *
 * @package Content\View
 * @property \Cake\View\View $View
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
     * @var \Cake\View\View
     */
    protected $_View;

    /**
     * @var \Cake\Controller\Controller
     */
    protected $_Controller;

    /**
     * Constructor.
     * @param \Cake\View\View|\Cake\Controller\Controller|null $parent Parent View or Controller instance
     * @param \Cake\Http\ServerRequest $request
     * @param \Cake\Http\Response $response
     * @param \Cake\Event\EventManager $eventManager
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
        } elseif ($parent instanceof Controller) {
            $this->_Controller = $parent;
        }
    }

    public function setPlugin($plugin)
    {
        $this->viewBuilder()->setPlugin($plugin);

        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    public function setArgs($args)
    {
        $this->args = $args;

        return $this;
    }

    /**
     * Render the cell.
     *
     * @param string|null $template Custom template name to render. If not provided (null), the last
     * value will be used. This value is automatically set by `CellTrait::cell()`.
     * @return string The rendered cell.
     * @throws \Cake\View\Exception\MissingCellViewException When a MissingTemplateException is raised during rendering.
     */
    public function render(?string $template = null): string
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
                    static::class,
                    $this->action
                ));
            }

            $builder = $this->viewBuilder();

            if (
                $template !== null &&
                strpos($template, '/') === false &&
                strpos($template, '.') === false
            ) {
                $template = Inflector::underscore($template);
            }
            if ($template === null) {
                $template = $builder->getTemplate() ?: null;
            }
            if ($template === null) {
                $template = $this->action;
            }
            $builder->setLayout(null)
                ->setTemplate($template);

            $className = static::class;
            $namePrefix = '\View\Module\\';
            $name = substr($className, strpos($className, $namePrefix) + strlen($namePrefix));
            $name = substr($name, 0, -6);
            if (!$builder->getTemplatePath()) {
                //debug('Module' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $name));
                $builder->setTemplatePath('Module' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $name));
            }

            $this->View = $this->createView();
            try {
                $html = $this->View->render($template);

                return $html;
            } catch (MissingTemplateException $e) {
                throw new MissingCellViewException(['file' => $template, 'name' => $name]);
            }
        };

        if ($cache) {
            return Cache::remember($cache['key'], $render, $cache['config']);
        }

        return $render();
    }

    /**
     * @param null $viewClass
     * @return \Cake\View\View
     */
    public function createView(?string $viewClass = null): View
    {
        $builder = $this->viewBuilder();

        //if ($this->plugin) {
        //    $builder->setPlugin($this->plugin);
        //}

        if ($this->_View) {
            if (!empty($this->_View->helpers()->loaded())) {
                $builder->setHelpers($this->_View->helpers()->loaded());
            }

            if ($this->_View->getTheme()) {
                $builder->setTheme($this->_View->getTheme());
            }

            $class = get_class($this->_View);
            $builder->setClassName($class);
        } elseif ($this->_Controller) {
            $builder->setHelpers($this->_Controller->viewBuilder()->getHelpers());
            $builder->setTheme($this->_Controller->viewBuilder()->getTheme());
            $builder->setClassName($this->_Controller->viewBuilder()->getClassName());
        }

        return $builder->build(
            $this->viewVars,
            $this->request,
            $this->response,
            null //$this->getEventManager()
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
    public function schema(?ViewModuleSchema $schema = null)
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
        foreach ($this->getSchema()->fields() as $field) {
            $options = $this->getSchema()->options($field);
            if (!$options || !isset($options['options'])) {
                continue;
            }

            $optionsField = $field;
            $optionsField = substr($optionsField, -3) == '_id' ? substr($optionsField, 0, -3) : $optionsField;

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
    public function validator(?Validator $validator = null)
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
        $validator = $this->getValidator();
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
    public function getErrors()
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
