<?php
declare(strict_types=1);

namespace Cupcake\Ui;

use Cake\Core\StaticConfigTrait;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventListenerInterface;
use Cake\View\View;

class Ui implements EventListenerInterface
{
    /**
     * @var \Cake\View\View
     */
    protected $view;

    /**
     * @var array Map of sub-elements
     */
    protected $elements = [];

    /**
     * @var array Call stack
     */
    protected $stack = [];

    /**
     * Ui constructor.
     *
     * @param \Cake\View\View $view
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param string $block
     * @param string|\Cupcake\Ui\UiElementInterface $uiElement
     * @return $this
     */
    public function add(string $block, $uiElement)
    {
        if (is_string($uiElement) && class_exists($uiElement)) {
            $uiElement = new $uiElement();
        }

        if (!($uiElement instanceof UiElementInterface)) {
            throw new \Exception(sprintf("The UiElement for '%s' block does not implement UiElementInterface",$block));
        }
        $this->elements[$block][] = $uiElement;

        return $this;
    }

    /**
     * Render a sub-elements.
     *
     * @param string $block
     * @return string
     */
    public function fetch(string $block): string
    {
        if (in_array($block, $this->stack)) {
            return sprintf("UI BLOCK RECURSION for block '%s': UI call stack: [%s]", $block, join(' > ', $this->stack));
        }
        array_push($this->stack, $block);

        $out = "";
        if (isset($this->elements[$block])) {
            /** @var \Cupcake\Ui\UiElementInterface $element */
            foreach ($this->elements[$block] as $element) {
                $out .= $element->render($this->view);
            }
        }

        array_pop($this->stack);

        return $out;
    }

    public function implementedEvents(): array
    {
        return [];
    }

    public function __debugInfo()
    {
        return [
            'elements' => $this->elements,
            'stack' => $this->stack,
        ];
    }
}
