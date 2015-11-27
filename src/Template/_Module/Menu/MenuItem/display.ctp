<?php
/**
 * Menu/menu_item Element
 *
 * Renders a simple menu item.
 */
$item = (isset($item)) ? $item : null;
$level = (isset($level)) ? $level : 0;

if (!$item) {
    debug(sprintf("No menu item given"));
    return;
}

$item['title'] = (isset($item['title'])) ? $item['title'] : __('Untitled Menu Item');
$item['url'] = (isset($item['url'])) ? $item['url'] : false;
$item['attr'] = (isset($item['attr'])) ? $item['attr'] : [];

?>
<?php
/**
 * Item with children
 */
if (isset($item['_children']) && !empty($item['_children'])): ?>
    <li class="submenu <?= sprintf('level-%s', $level) ?>">
        <?= $this->Html->link( $item['title'], $item['url'], $item['attr']); ?>
        <?= $this->module('Banana.Menu/MenuList', ['menu' => $item['_children'], 'level' => $level + 1]); ?>
    </li>
<?php
/**
 * Item without children
 */
else:
    ?>
    <li class="<?= sprintf('level-%s', $level) ?>">
    <?= $this->Html->link( $item['title'], $item['url'], $item['attr']); ?>
    </li>
<?php
endif;