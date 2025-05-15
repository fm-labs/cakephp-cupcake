<?php
declare(strict_types=1);

namespace Cupcake\Ui;

use Cake\Utility\Inflector;

trait UiElementTrait
{
    /**
     * @var string|null The view element name
     */
    protected ?string $elementName = '';

    protected string $elementBase = 'ui/';

    /**
     * Get the title for the panel.
     *
     * @return string
     */
    public function title(): string
    {
        [$ns, $name] = namespaceSplit(static::class);
        $name = substr($name, 0, strlen('Element') * -1);

        return Inflector::humanize(Inflector::underscore($name));
    }

    /**
     * Get the element name for the panel.
     *
     * @return string
     */
    public function elementName(): string
    {
        if ($this->elementName) {
            return $this->elementName;
        }

        [$ns, $name] = namespaceSplit(static::class);
        $elementName = $this->elementBase . Inflector::underscore($name);
        if ($this->plugin) {
            $elementName = $this->plugin . '.' . $elementName;
        }

        return $elementName;
    }
}
