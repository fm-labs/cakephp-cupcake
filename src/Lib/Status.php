<?php
namespace Banana\Lib;

/**
 * Class Status
 *
 * @package Banana\Lib
 */
class Status
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
     */
    public function toHtml()
    {
        return sprintf('<span class="label label-%s">%s</span>', $this->_class, $this->_label);
    }
}
