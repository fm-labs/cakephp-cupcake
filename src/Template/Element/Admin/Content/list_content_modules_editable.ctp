<?php foreach ($contentModules as $contentModule): ?>
    <?php $module = $contentModule->module; ?>
    <div class="ui top attached segment">
        <?= h($module->path); ?>
        <?= $this->Html->link('Edit', ['action' => 'edit_module', $module->id], ['target' => '_blank']); ?>
    </div>
    <div class="ui bottom attached segment">
        <?php echo $this->cell('Banana.ModuleRenderer', ['module' => $module]); ?>
    </div>
<?php endforeach; ?>