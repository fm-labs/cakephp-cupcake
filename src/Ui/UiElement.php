<?php
declare(strict_types=1);

namespace Cupcake\Ui;

use Cake\Utility\Inflector;

abstract class UiElement implements UiElementInterface
{
    use UiElementTrait;

    public $plugin = null;

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
    public function render(\Cake\View\View $view): string
    {
        $html = "";
        if ($view->elementExists($this->elementName())) {
            $html .= $view->element($this->elementName(), $this->data());
        }

        return $html;
    }
}
