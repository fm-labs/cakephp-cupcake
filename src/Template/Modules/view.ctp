<div class="modules view">
    <h1>Module: <?= h($module->name) ?></h1>
    [ ID <?= h($module->id) ?> ]
    <hr />
    <?php echo $this->element($module->viewElement, $module->viewData, $module->viewOptions); ?>
    <hr />
    <?php debug($module->params_arr); ?>
    <?php debug($module->viewElement); ?>
    <?php debug($module->viewData); ?>
    <?php debug($module->viewOptions); ?>
    <hr />
    <?php debug($module); ?>
</div>