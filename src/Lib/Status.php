<?php
declare(strict_types=1);

namespace Cupcake\Lib;

use JsonSerializable;

/**
 * Class Status
 *
 * @package Banana\Lib
 */
class Status implements JsonSerializable
{
    /**
     * @var int
     */
    protected int $_status;

    /**
     * @var string
     */
    protected string $_label;

    /**
     * @var string
     */
    protected string $_class;

    /**
     * @param int $status
     * @param string|null $label
     * @param string|null $class
     */
    public function __construct(int $status, ?string $label = null, ?string $class = null)
    {
        $this->_status = $status;

        $label = $label ?: $status;
        $this->_label = $label;

        $class = $class ?: 'default';
        $this->_class = $class;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->_status;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->_class;
    }

    /**
     * @return string|int
     */
    public function getLabel(): int|string
    {
        return $this->_label;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getStatus();
    }

//    /**
//     * @return string
//     * @deprecated Use a helper instead
//     */
//    public function toHtml()
//    {
//        return sprintf('<span class="label label-%s">%s</span>', $this->_class, $this->_label);
//    }

    public function toArray()
    {
        return [
            'class' => $this->_class,
            'label' => $this->_label,
            'status' => $this->_status,
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
