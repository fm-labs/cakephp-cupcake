<?php
declare(strict_types=1);

namespace Cupcake\Ui;

use Cake\View\View;

abstract class UiElement implements UiElementInterface
{
    use UiElementTrait;

    /**
     * Plugin name.
     *
     * @var string|null
     */
    public ?string $plugin = null;

    /**
     * View instance reference.
     *
     * @var \Cake\View\View
     */
    protected View $_View;

    protected Ui $_Ui;

    /**
     * @param \Cupcake\Ui\Ui $ui
     */
    public function __construct(Ui $ui)
    {
        $this->_Ui = $ui;
        $this->_View = $this->_Ui->getView();
        $this->initialize();
    }

    /**
     * @return void
     */
    public function initialize(): void
    {
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
     * Render the element.
     *
     * @return string
     */
    public function render(): string
    {
        $html = '';
        if ($this->_View->elementExists($this->elementName())) {
            $html .= $this->_View->element($this->elementName(), $this->data());
        }

        return $html;
    }
}
