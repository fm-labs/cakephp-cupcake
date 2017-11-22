<?php

namespace Banana\Menu;

use Cake\Collection\Collection;

/**
 * Class MenuItem
 * @package Banana\Menu
 *
 * @property string $title Title
 * @property mixed $url Url
 * @property array $attr Attributes
 * @property Menu $children Children
 *
 */
class MenuItem implements \ArrayAccess
{
    /**
     * @var string
     */
    protected $_title;

    /**
     * @var null|string|array
     */
    protected $_url;

    /**
     * @var array
     */
    protected $_attr;

    /**
     * @var array
     */
    protected $_children;

    /**
     * @param $title
     * @param null $url
     * @param array $attr
     * @param array $children
     */
    public function __construct($title, $url = null, array $attr = [], $children = [])
    {
        if (is_array($title)) {
            if (isset($title['data-icon'])) {
                $title['attr'] = ['data-icon' => $title['data-icon']];
                unset($title['data-icon']);
            }

            extract($title, EXTR_IF_EXISTS);
        }

        $this->_title = $title;
        $this->_url = $url;
        $this->_attr = $attr;

        $this->setChildren($children);
    }

    /**
     * @return array|string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @return array|null|string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->_attr;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * @param Menu|Collection|array $children
     */
    public function setChildren($children)
    {
        if (is_array($children)) {
            $_children = [];
            foreach($children as $child) {
                $_children[] = MenuItem::fromArray($child);
            }
            $children = $_children;
        }

        $this->_children = $children;
    }

    public function addChild($title, $url = null, $attr = [], $children = [])
    {
        if (is_array($title)) {
            $item = MenuItem::fromArray($title);
        } else {
            $item = new MenuItem($title, $url, $attr, $children);
        }

        $this->_children[spl_object_hash($item)] = $item;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->_title,
            'url' => $this->_url,
            'attr' => $this->_attr,
            'children' => $this->_children
        ];
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return in_array($offset, ['title', 'url', 'attributes', 'attr', 'children', '_children']);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        switch ($offset) {
            case 'title':
                return $this->getTitle();
            case 'url':
                return $this->getUrl();
            case 'attributes':
            case 'attr':
                return $this->getAttributes();
            case 'children':
            case '_children': // legacy
                return $this->getChildren();
            default:
                return null;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @throws \RuntimeException
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        //@TODO Remove offsetSet method and restore read-only mode
        //throw new \RuntimeException('Can not unset value for this object');
        switch ($offset) {
            case 'title':
            case 'url':
            case 'attr':
                $key = "_" . $offset;
                $this->{$key} = $value;
                break;
            case 'children':
                $this->setChildren($value);
                break;
            default:
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @throws \RuntimeException
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Can not unset value for this object');
    }

    /**
     * Create menu item from array
     *
     * Accepts:
     *
     * [TITLE, URL, ATTR, CHILDREN]
     *
     * ['title' => TITLE, 'url' => URL, 'children' => CHILDREN, 'foo' =>'bar']
     *
     * ['title' => TITLE, 'url' => URL, 'children' => CHILDREN, 'attr' => ['foo' => 'bar']]
     */
    static public function fromArray(array $item)
    {
        $title = $url = $attr = $children = null;
        if (isset($item[0])) {
            list($title, $url, $attr, $children) = $item;
        } else {
            $item['foo'] = 'bar';
            $title = (isset($item['title'])) ? $item['title'] : null;
            $url = (isset($item['url'])) ? $item['url'] : null;
            $children = (isset($item['children'])) ? $item['children'] : [];

            $attr = (isset($item['attr'])) ? $item['attr'] : [];
            $attr += array_diff_key($item, ['title' => null, 'url' => null, 'children' => null, 'attr' => null]);
        }

        return new MenuItem($title, $url, (array) $attr, (array) $children);
    }
}
