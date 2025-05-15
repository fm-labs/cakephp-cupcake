<?php
declare(strict_types=1);

namespace Cupcake\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Class MenuHelper
 *
 * @package Cupcake\View\Helper
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class MenuHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * @var array
     */
    public array $helpers = ['Html'];

    /**
     * Default config for this class
     *
     * @var array
     */
    protected array $_defaultConfig = [
        'templates' => [
            'menuList' => '<ul{{attrs}}>{{content}}</ul>',
            'menuListItem' => '<li{{attrs}}>{{content}}{{submenu}}</li>',
        ],
    ];

    /**
     * @var array
     */
    protected $_menu;

    /**
     * @var array
     */
    protected $_options = [];

    /**
     * @var array
     */
    protected $_itemOptions = [];

    /**
     * @var int Menu recursion level
     */
    protected $_level = 0;

    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
    }

    /**
     * @param array $menu The menu list.
     * @param array $options The menu options.
     * @param array $itemOptions The menu item options.
     * @return $this
     */
    public function create(array $menu, array $options = [], array $itemOptions = [])
    {
        $options += ['class' => null, 'templates' => []];

        if ($options['templates']) {
            $this->templater()->add($options['templates']);
        }
        unset($options['templates']);

        $this->_menu = $menu;
        $this->_options = $options;
        $this->_itemOptions = $itemOptions;

        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return $this->_renderMenu($this->_menu, $this->_options, $this->_itemOptions);
    }

    /**
     * @param array $menu The menu list.
     * @param array $options The menu list render options.
     * @param array $itemOptions The menu item options.
     * @return string
     */
    protected function _renderMenu(array $menu, array $options = [], array $itemOptions = []): string
    {
        $options['data-level'] = $this->_level;

        $itemsHtml = '';
        foreach ($menu as $item) {
            try {
                $itemsHtml .= $this->_renderItem($item, $itemOptions);
            } catch (\Exception $ex) {
                if (Configure::read('debug')) {
                    $itemsHtml .= $this->templater()->format('menuListItem', [
                        'attrs' => $this->templater()->formatAttributes([
                            'title' => $ex->getMessage(),
                        ]),
                        'content' => '!' . $item['title'],
                        'submenu' => '',
                    ]);
                }
            }
        }

        return $this->templater()->format('menuList', [
            'attrs' => $this->templater()->formatAttributes($options),
            'content' => $itemsHtml,
        ]);
    }

    /**
     * @param array $item The menu item.
     * @param array $options The menu item render options.
     * @return string
     */
    protected function _renderItem($item, array $options = []): string
    {
        $options['data-level'] = $this->_level;
        $item += ['title' => null, 'url' => null, 'attr' => []];
        $hasChildren = isset($item['children']) && count($item['children']) > 0 ? true : false;

        // title
        $title = $this->Html->tag('span', $item['title'], $item['attr']);
        if ($item['url']) {
            $title = $this->Html->link($item['title'], $item['url'], $item['attr']);
        }

        // children
        $submenu = '';
        if ($hasChildren) {
            $this->_level++;
            $submenu = $this->_renderMenu($item['children']);
            $this->_level--;
        }

        return $this->templater()->format('menuListItem', [
            'attrs' => $this->templater()->formatAttributes($options),
            'content' => $title,
            'submenu' => $submenu,
        ]);
    }
}
