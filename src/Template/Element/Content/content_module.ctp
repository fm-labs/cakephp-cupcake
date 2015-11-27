<?php
$module = $contentModule->module;
$cell = ($contentModule->template) ? $module->path . 'Module::' . $contentModule->template : $module->path.'Module';
?>
<!-- Content Module #<?= $contentModule->id ?> -->
<div class="mod">
    <div class="debug">
        Module: <?= h($module->name) ?>  [ ID <?= h($module->id) ?> ]<br />
        [ CLASS <?= h(get_class($module)) ?> ]<br />
        [ CELL <?= h($cell) ?> ]
    </div>
    <?php echo $this->cell($cell , [$module]); ?>
</div>
<!-- EOF Content Module #<?= $contentModule->id ?> -->