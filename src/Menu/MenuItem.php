<?php
declare(strict_types=1);

namespace Cupcake\Menu;

/**
 * Class MenuItem.
 *
 * @package Cupcake\Menu
 * @property string $title Title
 * @property mixed $url Url
 * @property array $attr Attributes
 * @property \Cupcake\Menu\MenuItemCollection $children Children
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
     * @var \Cupcake\Menu\MenuItemCollection
     */
    protected $_children;

    /**
     * Constructor.
     * Examples:
     *   TITLE, URL, ATTR, CHILDREN
     *   ['title' => TITLE, 'url' => URL, 'children' => CHILDREN, 'foo' =>'bar']
     *   ['title' => TITLE, 'url' => URL, 'children' => CHILDREN, 'attr' => ['foo' => 'bar']]
     *
     * @param string|array $title Item title
     * @param string|array|null $url Item url
     * @param array $attr Item attributes
     * @param array|\Cupcake\Menu\MenuItemCollection $children List or collection of child items
     */
    public function __construct($title, $url = null, array $attr = [], $children = [])
    {
        if (is_array($title)) {
            $defaults = ['title' => null, 'url' => null, 'children' => null, 'attr' => null];
            $tmp = $title;

            $title = $tmp['title'] ?? null;
            $url = $tmp['url'] ?? null;
            $children = $tmp['children'] ?? [];
            $attr = $tmp['attr'] ?? [];
            $attr += array_diff_key($tmp, $defaults);
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
     * @return \Cupcake\Menu\MenuItemCollection
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * @param \Cupcake\Menu\MenuItemCollection|array $children Menu item children
     * @return $this
     */
    public function setChildren($children)
    {
        $this->_children = new MenuItemCollection();

        if ($children instanceof MenuItemCollection) {
            $this->_children = $children;
        } elseif (is_array($children)) {
            $this->_children->addItems($children);
        } else {
            throw new \InvalidArgumentException(
                "Invalid MenuItem children parameter. MUST be instance of \Cupcake\Menu\MenuItemCollection or array."
            );
        }

        return $this;
    }

    /**
     * Alias for setChildren (auto-append)
     *
     * @param \Cupcake\Menu\MenuItemCollection|array $children Menu item children
     * @return $this
     */
    public function addChildren($children)
    {
        if (!$this->_children) {
            $this->_children = new MenuItemCollection();
        }
        $this->_children->addItems($children);

        return $this;
    }

    /**
     * @param string|array $title Child item title
     * @param string|array|null $url Child item url
     * @param array $attr Child item attributes
     * @param array $children Child item children
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
    public function toArray(): array
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
     *
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
        return in_array($offset, ['title', 'url', 'attributes', 'attr', 'children']);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
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
            case 'attr':
            case 'attributes':
                return $this->getAttributes();
            case 'children':
                return $this->getChildren();
            default:
                return null;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
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
        throw new \RuntimeException('Can not set value for this object');
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
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
}
