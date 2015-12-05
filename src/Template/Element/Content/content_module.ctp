<?php
$module = $contentModule->module;
$cell = ($contentModule->template) ? $module->path . 'Module::' . $contentModule->template : $module->path.'Module';
$cellData = [];
$cellOptions = ['page_id' => $page_id, 'section' => $section, 'module' => $module]
?>
<!-- Content Module #<?= $contentModule->id ?> -->
<div class="mod">
    <div class="debug">
        [ MODULE #<?= h($module->id) ?>: <?= h($module->name) ?>  ]<br />
        [ CLASS: <?= h(get_class($module)) ?> ]<br />
        [ CELL: <?= h($cell) ?> ]<br />
        [ SECTION: <?= (isset($section)) ? $section: 'NOSECTION'; ?> ]<br />
        [ PAGEID: <?= (isset($page_id)) ? $page_id: 'NOPAGEID'; ?> ]
    </div>
    <?php echo $this->cell($cell , $cellData, $cellOptions); ?>
</div>
<!-- EOF Content Module #<?= $contentModule->id ?> -->