<?php

namespace Cupcake\Ui;

interface UiElementInterface
{
    /**
     * Get the title.
     *
     * @return string
     */
    public function title(): string;

    /**
     * Get the element name.
     *
     * @return string
     */
    public function elementName(): string;

    /**
     * Get the data.
     *
     * @return array
     */
    public function data(): array;

    /**
     * Render html
     * @param \Cake\View\View $view
     * @return string
     */
    //public function render(\Cake\View\View $view): string;
}
