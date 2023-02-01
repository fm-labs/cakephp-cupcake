<?php


namespace Cupcake\Model;


class AttributesSchema implements \ArrayAccess
{
    protected $_schema = [];

    public function __construct(array $attributes = [])
    {
        $this->addAttributes($attributes);
    }

    public function addAttributes(array $attributes)
    {
        foreach ($attributes as $key => $options) {
            $this->addAttribute($key, $options);
        }

        return $this;
    }

    public function addAttribute(string $key, array $options = [])
    {
        $options += ['type' => 'string', 'default' => null, 'required' => null, 'input' => []];
        $this->_schema[$key] = $options;

        return $this;
    }

    public function getAttribute(string $key): ?array
    {
        return $this->_schema[$key] ?? null;
    }

    public function hasAttribute(string $key): bool
    {
        return array_key_exists($key, $this->_schema);
    }

    public function getKeys(): array
    {
        return array_keys($this->_schema);
    }

    public function requireAttribute(string $key, bool $status = true)
    {
        $attr = $this->getAttribute($key) ?? [];
        $attr['required'] = $status;

        return $this->addAttribute($key, $attr);
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return $this->hasAttribute($offset);
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}
