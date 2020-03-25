<?php
declare(strict_types=1);

namespace Banana\Menu;

/**
 * Class MenuItem
 * @package Banana\Menu
 *
 * @property string $title Title
 * @property mixed $url Url
 * @property array $attr Attributes
 * @property \Banana\Menu\Menu $children Children
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
     * @var \Banana\Menu\Menu
     */
    protected $_children;

    /**
     * @param string|array $title
     * @param null $url
     * @param array $attr
     * @param \Banana\Menu\Menu|array $children
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
     * @param \Banana\Menu\Menu|array $children Menu item children
     * @param bool $append If True, append children instead of replacing existing items (default: false)
     * @return $this
     */
    public function setChildren($children, $append = false)
    {
        if ($append == false) {
            $this->_children = new Menu();
        }

        if ($children instanceof Menu) {
            $this->_children = $children;
        } elseif (is_array($children)) {
            $this->_children->addItems($children);
        }

        return $this;
    }

    /**
     * Alias for setChildren (auto-append)
     *
     * @param \Banana\Menu\Menu|array $children Menu item children
     * @return $this
     */
    public function addChildren($children)
    {
        return $this->setChildren($children, true);
    }

    /**
     * @return $this
     */
    public function addChild($title, $url = null, $attr = [], $children = [])
    {
        $this->_children->addItem($title, $url, $attr, $children);

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
            'children' => $this->_children->toArray(),
        ];
    }

    /**
     * @param string $key Property name
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
     * @return bool true on success or false on failure.
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
                break;
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
     *
     * @param array $item Menu item array
     * @return self
     */
    public static function fromArray(array $item)
    {
        $title = $url = null;
        $attr = $children = [];

        if (isset($item[0])) {
            [$title, $url, $attr, $children] = $item;
        } else {
            //$item['foo'] = 'bar';
            $title = $item['title'] ?? null;
            $url = $item['url'] ?? null;
            $children = $item['children'] ?? [];

            $attr = $item['attr'] ?? [];
            $attr += array_diff_key($item, ['title' => null, 'url' => null, 'children' => null, 'attr' => null]);
        }

        return new self($title, $url, $attr, $children);
    }
}
