<?php
/**
 * Menu Element
 *
 * Renders a simple menu structure.
 */

$menu       = isset($menu) ? $menu : false;
$class      = isset($class) ? $class : 'ui menu';
$id         = isset($id) ? $id : null;
$wrapper    = isset($wrapper) ? $wrapper : 'div';
//$levels     = isset($levels) ? $levels : -1;

if (!$menu) {
    debug("No menu set");
    return false;
}

$html = "";
foreach ($menu as $menuItem) {
    $html .= $this->element('Banana.Menu/menu_item', ['item' => $menuItem]);
}
echo $this->Html->tag($wrapper, $html, ['id' => $id, 'class' => $class]);
?>