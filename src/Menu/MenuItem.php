<?php
declare(strict_types=1);

namespace Cupcake\Menu;

use ArrayAccess;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class MenuItem.
 *
 * @package Cupcake\Menu
 * @property string $title Title
 * @property mixed $url Url
 * @property array $attr Attributes
 * @property \Cupcake\Menu\MenuItemCollection $children Children
 */
class MenuItem implements ArrayAccess
{
    /**
     * @var string
     */
    protected string $_title;

    /**
     * @var array|string|null
     */
    protected string|array|null $_url = null;

    /**
     * @var array
     */
    protected array $_attr;

    /**
     * @var \Cupcake\Menu\MenuItemCollection
     */
    protected MenuItemCollection $_children;

    /**
     * Constructor.
     * Examples:
     *   TITLE, URL, ATTR, CHILDREN
     *   ['title' => TITLE, 'url' => URL, 'children' => CHILDREN, 'foo' =>'bar']
     *   ['title' => TITLE, 'url' => URL, 'children' => CHILDREN, 'attr' => ['foo' => 'bar']]
     *
     * @param array|string $title Item title
     * @param array|string|null $url Item url
     * @param array $attr Item attributes
     * @param \Cupcake\Menu\MenuItemCollection|array $children List or collection of child items
     */
    public function __construct(string|array $title, string|array|null $url = null, array $attr = [], array|MenuItemCollection $children = [])
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
    public function getTitle(): array|string
    {
        return $this->_title;
    }

    /**
     * @return array|string|null
     */
    public function getUrl(): array|string|null
    {
        return $this->_url;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->_attr;
    }

    /**
     * @return \Cupcake\Menu\MenuItemCollection
     */
    public function getChildren(): MenuItemCollection
    {
        return $this->_children;
    }

    /**
     * @param \Cupcake\Menu\MenuItemCollection|array $children Menu item children
     * @return $this
     */
    public function setChildren(MenuItemCollection|array $children)
    {
        $this->_children = new MenuItemCollection();

        if ($children instanceof MenuItemCollection) {
            $this->_children = $children;
        } elseif (is_array($children)) {
            $this->_children->addItems($children);
        } else {
            throw new InvalidArgumentException(
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
    public function addChildren(MenuItemCollection|array $children)
    {
        if (!$this->_children) {
            $this->_children = new MenuItemCollection();
        }
        $this->_children->addItems($children);

        return $this;
    }

    /**
     * @param \Cupcake\Menu\MenuItem|array|string $title Child item title
     * @param array|string|null $url Child item url
     * @param array $attr Child item attributes
     * @param array $children Child item children
     * @return $this
     */
    public function addChild(string|array|MenuItem $title, string|array|null $url = null, array $attr = [], array $children = [])
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
    public function __get(string $key): mixed
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
    public function offsetExists(mixed $offset): bool
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
    public function offsetGet(mixed $offset): mixed
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
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new RuntimeException('Can not set value for this object');
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
    public function offsetUnset(mixed $offset): void
    {
        throw new RuntimeException('Can not unset value for this object');
    }
}
