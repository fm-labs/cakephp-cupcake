<?php
/**
 * Page content extended view template
 *
 * Wraps the page in a <div> container
 */
if (!isset($page)) {
    echo $this->Html->div('page error', 'Can not render content page: No page set');
    return;
}

$attr = [
    'class' => trim('page ' . $page->cssclass),
    'id' => ($page->cssid) ?: 'page' . $page->id
];

$pageDiv = $this->Html->div(null, $this->fetch('content'), $attr);
//echo $this->Content->userHtml($pageDiv);
echo $pageDiv;
?>
