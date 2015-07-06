<?php
$module = $this->get('module');
$modulePath = $this->get('modulePath');
$moduleParams = $this->get('moduleParams');
$moduleHtmlClass = $this->get('moduleHtmlClass');
$moduleTemplate = $this->get('moduleTemplate');
?>
<div class="<?= $moduleHtmlClass; ?>">
    <?php
    /**
     * Module: <?= h($module->title) ?> [<?= h($module->path); ?>#<?= h($module->name); ?>]
     */
    ?>
    <?php
    $module = $this->module($modulePath, [], ['params' => $moduleParams]);
    $module->template = ($moduleTemplate) ? $moduleTemplate : 'display';
    echo $module;
    ?>
</div>