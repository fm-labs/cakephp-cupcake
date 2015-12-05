<?php $this->Html->addCrumb(__('Content Modules'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($contentModule->id); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('Edit {0}', __('Content Module')),
                ['action' => 'edit', $contentModule->id],
                ['class' => 'item', 'icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __('Delete {0}', __('Content Module')),
                ['action' => 'delete', $contentModule->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $contentModule->id)]) ?>

            <?= $this->Ui->link(
                __('List {0}', __('Content Modules')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __('New {0}', __('Content Module')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui item dropdown">
                <div class="menu">
                    <?= $this->Ui->link(
                        __('List {0}', __('Modules')),
                        ['controller' => 'Modules', 'action' => 'index'],
                        ['class' => 'item', 'icon' => 'list']
                    ) ?>
                    <?= $this->Ui->link(
                        __('New {0}', __('Module')),
                        ['controller' => 'Modules', 'action' => 'add'],
                        ['class' => 'item', 'icon' => 'add']
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="contentModules view">
    <h2 class="ui top attached header">
        <?= h($contentModule->id) ?>
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
            <td><?= __('Refscope') ?></td>
            <td><?= h($contentModule->refscope) ?></td>
        </tr>
        <tr>
            <td><?= __('Module') ?></td>
            <td><?= $contentModule->has('module') ? $this->Html->link($contentModule->module->name, ['controller' => 'Modules', 'action' => 'view', $contentModule->module->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __('Section') ?></td>
            <td><?= h($contentModule->section) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($contentModule->id) ?></td>
        </tr>
        <tr>
            <td><?= __('Refid') ?></td>
            <td><?= $this->Number->format($contentModule->refid) ?></td>
        </tr>

    </table>
</div>
