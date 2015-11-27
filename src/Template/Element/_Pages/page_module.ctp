<div class="page-module">
    <div class="page-module-debug">
        Module: <?= h($module->name) ?>  [ ID <?= h($module->id) ?> ]<br />
        [ CLASS <?= h(get_class($module)) ?> ]<br />
        [ ELEMENT <?= h($module->viewElement) ?> ]
    </div>
    <?php echo $this->element($module->viewElement, $module->viewData, $module->viewOptions); ?>
    <?php //debug($module->viewData); ?>
    <?php //debug($module->viewOptions); ?>
    <hr />
</div>