<?php if (!empty($contentModules)): ?>
<?php foreach ($contentModules as $contentModule): ?>
    <?php $module = $contentModule->module; ?>
    <?php if ($contentModule->section != $section) continue; ?>
    <div class="ui top attached segment">
        [<?= h($contentModule->section); ?>] <?= h($module->path); ?>
        <?= $this->Ui->link('Edit', [
            'controller' => 'ModuleBuilder',
            'action' => 'edit',
            $module->id,
            'refscope' => $contentModule->refscope,
            'refid' => $contentModule->refid
        ], ['icon' => 'edit', 'target' => '_blank']); ?>
        <?= $this->Ui->link('Remove', ['action' => 'remove_module', $module->id], ['icon' => 'trash']); ?>
    </div>
    <div class="ui bottom attached segment">
        <?php //echo $this->cell('Banana.ModuleRenderer', ['module' => $module]); ?>
        <?php debug($module->params_arr); ?>
    </div>
<?php endforeach; ?>
<?php endif; ?>

<hr />
<h2>Add content module</h2>
- List of available modules -
<br />

<br /><br />
- Create a new module (and link with this page) -
<br />
<?= $this->Html->link(__('Add a new content module to section {0}', $section), ['action' => 'add_module', $content->id]); ?>

