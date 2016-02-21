<?php
$module = $contentModule->module;
$cell = ($contentModule->template) ? $module->path . 'Module::' . $contentModule->template : $module->path.'Module';
$cellData = [];
$cellOptions = compact('refscope', 'refid', 'section', 'module');

$moduleHtml = $this->cell($cell , $cellData, $cellOptions);

// output module without module container
$nowrap = ($contentModule->cssclass == '_nowrap_');
if ($nowrap) {
    echo $moduleHtml;
    return;
}

$attr = [
    'class' => trim('mod ' . $contentModule->cssclass),
    'id' => ($contentModule->cssid) ?: 'mod' . $contentModule->id
];

echo $this->Html->div(null, $moduleHtml, $attr);
return;
?>
<!-- Content Module #<?= $contentModule->id ?> -->
<div class="mod">
    <div class="debug">
        [ MODULE #<?= h($module->id) ?>: <?= h($module->name) ?>  ]<br />
        [ CLASS: <?= h(get_class($module)) ?> ]<br />
        [ CELL: <?= h($cell) ?> ]<br />
        [ SECTION: <?= (isset($section)) ? $section: 'NOSECTION'; ?> ]<br />
        [ REFSCOPE: <?= (isset($refscope)) ? $refid: 'NOREFSCOPE'; ?> ]<br />
        [ REFID: <?= (isset($refid)) ? $refid: 'NOREFID'; ?> ]
    </div>
    <?php echo $this->cell($cell , $cellData, $cellOptions); ?>
</div>
<!-- EOF Content Module #<?= $contentModule->id ?> -->