<?php
namespace Banana\Lib;


class Status
{
    protected $_status;
    protected $_label;
    protected $_class;

    public function __construct($status, $label = null, $class = null)
    {
        $this->_status = $status;

        $label = ($label) ?: $status;
        $this->_label = $label;

        $class = ($class) ?: 'default';
        $this->_class = $class;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function getClass()
    {
        return $this->_class;
    }

    public function __toString()
    {
        return $this->_label;
    }

    public function toHtml()
    {
        return sprintf('<span class="label label-%s">%s</span>', $this->_class, $this->_label);
    }
}