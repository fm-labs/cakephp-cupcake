<?php
/**
 * Renders an html element
 *
 * Html elements are located at Templates/Modules/Html with a .ctp file extension
 *
 * Params:
 * - elementPath: (string) Relative path to Templates/Modules/Html
 */
if ($params['elementPath']) {
    $elementPath = 'Modules/Html/' . $params['elementPath'];
    echo $this->element($elementPath);

} elseif (\Cake\Core\Configure::read('debug')) {
    echo __('HtmlElement not found: ' . $params['elementPath']);
}