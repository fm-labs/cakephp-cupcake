<?php
namespace Cupcake\Ui;

class UiGroup implements UiElementInterface
{
    use UiElementTrait;

    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->elements[$this->name] = [];
    }

    public function data(): array
    {
        return [
            'name' => null,
            'blocks' => array_keys($this->elements[$this->name])
        ];
    }

    public function render(): string
    {
        $out = "";
        foreach ($this->elements as $block => $elements) {
            foreach($elements as $element) {
                $out .= $element->render();
            }
        }
        return $out;
    }
}