<?php
declare(strict_types=1);

namespace Cupcake\Menu;

use Countable;
use Iterator;

/**
 * Class Menu
 *
 * @package Cupcake\Menu
 */
class MenuItemCollection implements Iterator, Countable
{
    /**
     * @var array<\Cupcake\Menu\MenuItem>
     */
    protected array $_items = [];

    /**
     * @var array Items keys cache. Used by iterator
     */
    private array $_it;

    /**
     * @var int Current position in iterator array
     */
    private int $_itpos;

    /**
     * @param array $items Initial item list
     */
    public function __construct(array $items = [])
    {
        $this->addItems($items);
    }

    /**
     * @return array<\Cupcake\Menu\MenuItem>
     */
    public function getItems(): array
    {
        return $this->_items;
    }

    /**
     * @param \Cupcake\Menu\MenuItem|array|string $title A menu item array or object or title string
     * @param string|null $url Item url
     * @param array $attr Item attributes
     * @param \Cupcake\Menu\MenuItemCollection|array $children Item subitems
     * @return $this
     */
    public function addItem(string|array|MenuItem $title, ?string $url = null, array $attr = [], array|MenuItemCollection $children = [])
    {
        $item = $title instanceof MenuItem ? $title : new MenuItem($title, $url, $attr, $children);
        $hash = spl_object_hash($item);
        $this->_items[$hash] = $item;

        return $this;
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
     * @param \Cupcake\Menu\MenuItem $item The instance of the item to remove
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
     * @return array
     */
    public function toArray(): array
    {
        $list = [];
        foreach ($this->_items as $item) {
            $list[] = $item->toArray();
        }

        return $list;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->_items);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element.
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current(): mixed
    {
        $pos = $this->_itpos;

        return $this->_items[$this->_it[$pos]];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element.
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next(): void
    {
        $this->_itpos++;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element.
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key(): mixed
    {
        $pos = $this->_itpos;

        return $this->_it[$pos] ?? null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid.
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid(): bool
    {
        $pos = $this->_itpos;

        return isset($this->_it[$pos]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element.
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind(): void
    {
        $this->_it = array_keys($this->_items);
        $this->_itpos = 0;
    }
}
