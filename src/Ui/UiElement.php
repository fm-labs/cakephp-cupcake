<?php
declare(strict_types=1);

namespace Cupcake\Ui;

use Cake\Utility\Inflector;

abstract class UiElement implements UiElementInterface
{
    use UiElementTrait;

    /**
     * Plugin name.
     *
     * @var null|string
     */
    public $plugin = null;

    /**
     * View instance reference.
     *
     * @var \Cake\View\View
     */
    protected $_View;


    protected $_Ui;

    public function __construct(\Cupcake\Ui\Ui $ui)
    {
        $this->_Ui = $ui;
        $this->_View = $this->_Ui->getView();
        $this->initialize();
    }

    public function initialize(): void {
        // override in subclasses
    }

    /**
     * Get the data for the panel.
     *
     * @return array
     */
    public function data(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function render(): string
    {
        $html = "";
        if ($this->_View->elementExists($this->elementName())) {
            $html .= $this->_View->element($this->elementName(), $this->data());
        }
        return $html;
    }
}
