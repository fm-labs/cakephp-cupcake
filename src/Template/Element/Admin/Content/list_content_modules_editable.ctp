<?php if (!empty($contentModules)): ?>
<?php foreach ($contentModules as $contentModule): ?>
    <?php $module = $contentModule->module; ?>
    <?php if ($contentModule->section != $section) continue; ?>
    <div class="ui top attached segment">
        [<?= h($contentModule->section); ?>]
        [<?= h($module->path); ?>]
        <?= h($module->name); ?>
        <br />

        <?= $this->Ui->link('Edit Module', [
            'controller' => 'ModuleBuilder',
            'action' => 'edit',
            $module->id,
            'refscope' => $contentModule->refscope,
            'refid' => $contentModule->refid
        ], ['icon' => 'edit', 'target' => '_blank']); ?> |

        <?= $this->Ui->link('Edit Content Module',
            [ 'controller' => 'ContentModules', 'action' => 'edit', $contentModule->id ],
            ['icon' => 'edit', 'target' => '_blank']); ?>
        <?= $this->Ui->link('Remove Content Module',
            [ 'controller' => 'ContentModules', 'action' => 'delete', $contentModule->id ],
            ['icon' => 'trash']); ?>
    </div>
    <div class="ui bottom attached segment">
        <h4>Module Params</h4>
        <ul>
        <?php foreach($module->params_arr as $k => $v): ?>
            <li><?= h($k) ?>:<?= (is_array($v)) ? '[Array]' : $v; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endforeach; ?>
<?php endif; ?>
<!--
<hr />
<h2>Add content module</h2>
- List of available modules -
<br />

<br /><br />
- Create a new module (and link with this page) -
<br />
<?= $this->Html->link(__d('banana','Add a new content module to section {0}', $section), ['action' => 'add_module', $content->id]); ?>
-->

