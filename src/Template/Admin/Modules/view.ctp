<?php $this->Html->addCrumb(__('Modules'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($module->name); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('Edit {0}', __('Module')),
                ['action' => 'edit', $module->id],
                ['class' => 'item', 'icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __('Delete {0}', __('Module')),
                ['action' => 'delete', $module->id],
                ['class' => 'item', 'icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $module->id)]) ?>

            <?= $this->Ui->link(
                __('List {0}', __('Modules')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __('New {0}', __('Module')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui item dropdown">
                <div class="menu">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="modules view">
    <h2 class="ui top attached header">
        <?= h($module->name) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __('Label'); ?></th>
            <th><?= __('Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __('Name') ?></td>
            <td><?= h($module->name) ?></td>
        </tr>
        <tr>
            <td><?= __('Title') ?></td>
            <td><?= h($module->title) ?></td>
        </tr>
        <tr>
            <td><?= __('Path') ?></td>
            <td><?= h($module->path) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($module->id) ?></td>
        </tr>

        <tr class="text">
            <td><?= __('Params') ?></td>
            <td><?= $this->Text->autoParagraph(h($module->params)); ?></td>
        </tr>
    </table>
</div>
