<?php foreach ($contentModules as $contentModule): ?>
    <?php $module = $contentModule->module; ?>
    <?php if (isset($section) && $contentModule->section != $section) continue; ?>
    <div class="ui top attached segment">
        [<?= h($contentModule->section); ?>] <?= h($module->path); ?>
        <?= $this->Html->link('Edit', ['action' => 'edit_module', $module->id], ['target' => '_blank']); ?>
        <?= $this->Html->link('Remove', ['action' => 'remove_module', $module->id]); ?>
    </div>
    <div class="ui bottom attached segment">
        <?php echo $this->cell('Banana.ModuleRenderer', ['module' => $module]); ?>
    </div>
<?php endforeach; ?>