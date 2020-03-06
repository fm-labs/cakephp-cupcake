<?php

namespace Banana\Menu;

/**
 * Class Menu
 *
 * @package Banana\Menu
 */
class Menu implements \Iterator, \Countable
{
    /**
     * @var MenuItem[]
     */
    protected $_items = [];

    /**
     * @var array
     */
    protected $_attr;

    /**
     * @var array Items keys cache. Used by iterator
     */
    private $_it;

    /**
     * @var int Current position in iterator array
     */
    private $_itpos;

    /**
     * @param array $items Initial item list
     * @param array $attr Menu attributes
     */
    public function __construct($items = [], $attr = [])
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
        $this->_attr = $attr;
    }

    /**
     * @param string $key Attribute name
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * @param string $key Attribute name
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (isset($this->_attr[$key])) {
            return $this->_attr[$key];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->_attr;
    }

    /**
     * @return MenuItem[]
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * @param string|array|MenuItem $title A menu item array or object or title string
     * @param null $url Item url
     * @param array $attr Item attributes
     * @param array|Menu $children Item subitems
     * @return MenuItem
     */
    public function &addItem($title, $url = null, $attr = [], $children = [])
    {
        if ($title instanceof MenuItem) {
            $item = $title;
        } elseif (is_array($title)) {
            $item = MenuItem::fromArray($title);
        } else {
            $item = new MenuItem($title, $url, $attr, $children);
        }
        $hash = spl_object_hash($item);
        $this->_items[$hash] = $item;

        return $this->_items[$hash];
    }

    /**
     * @param array $items List of menu items
     * @return $this
     */
    public function addItems(array $items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }

        return $this;
    }

    /**
     * @param MenuItem $item The instance of the item to remove
     *
     * @return $this
     */
    public function removeItem(MenuItem $item)
    {
        $hash = spl_object_hash($item);
        if (isset($this->_items[$hash])) {
            unset($this->_items[$hash]);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_items);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $list = [];
        foreach ($this->_items as $item) {
            $list[] = $item->toArray();
        }

        return $list;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        $pos = $this->_itpos;

        return $this->_items[$this->_it[$pos]];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->_itpos++;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        $pos = $this->_itpos;

        return (isset($this->_it[$pos])) ? $this->_it[$pos] : null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        $pos = $this->_itpos;

        return (isset($this->_it[$pos])) ? true : false;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->_it = array_keys($this->_items);
        $this->_itpos = 0;
    }
}
