<?php
namespace Banana\Lib;

/**
 * Class Status
 *
 * @package Banana\Lib
 */
class Status implements \JsonSerializable
{
    /**
     * @var int
     */
    protected $_status;

    /**
     * @var string
     */
    protected $_label;

    /**
     * @var string
     */
    protected $_class;

    /**
     * @param int $status
     * @param null|string $label
     * @param null|string $class
     */
    public function __construct($status, $label = null, $class = null)
    {
        $this->_status = $status;

        $label = ($label) ?: $status;
        $this->_label = $label;

        $class = ($class) ?: 'default';
        $this->_class = $class;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->_class;
    }

    /**
     * @return int|string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getStatus();
    }

    /**
     * @return string
     * @deprecated Use a helper instead
     */
    public function toHtml()
    {
        return sprintf('<span class="label label-%s">%s</span>', $this->_class, $this->_label);
    }

    public function toArray()
    {
        return [
            'class' => $this->_class,
            'label' => $this->_label,
            'status' => $this->_status
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this->toArray();
    }
}
