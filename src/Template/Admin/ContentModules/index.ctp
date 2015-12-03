<?php $this->Html->addCrumb(__('Content Modules')); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('New {0}', __('Content Module')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui dropdown item">
                <i class="dropdown icon"></i>
                <i class="setting icon"></i>Actions
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

<div class="contentModules index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('refscope') ?></th>
            <th><?= $this->Paginator->sort('refid') ?></th>
            <th><?= $this->Paginator->sort('module_id') ?></th>
            <th><?= $this->Paginator->sort('section') ?></th>
            <th><?= $this->Paginator->sort('template') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($contentModules as $contentModule): ?>
        <tr>
            <td><?= $this->Number->format($contentModule->id) ?></td>
            <td><?= h($contentModule->refscope) ?></td>
            <td><?= $this->Number->format($contentModule->refid) ?></td>
            <td>
                <?= $contentModule->has('module')
                    ? $this->Html->link($contentModule->module->name, ['controller' => 'ModuleBuilder', 'action' => 'edit', $contentModule->module->id])
                    : '' ?>
            </td>
            <td><?= h($contentModule->section) ?></td>
            <td><?= h($contentModule->template) ?></td>
            <td class="actions">
                <div class="ui basic small buttons">
                    <div class="ui button">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $contentModule->id]) ?>
                    </div>
                    <div class="ui floating dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <?= $this->Ui->link(
                                __('Edit'),
                                ['action' => 'edit', $contentModule->id],
                                ['class' => 'item', 'icon' => 'edit']
                            ) ?>
                            <?= $this->Ui->postLink(
                                __('Delete'),
                                ['action' => 'delete', $contentModule->id],
                                ['class' => 'item', 'icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $contentModule->id)]
                            ) ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
    </table>
    <div class="paginator">
        <div class="ui pagination menu">
            <?= $this->Paginator->prev(__('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next')) ?>

            <div class="item">
                <?= $this->Paginator->counter() ?>
            </div>
        </div>
    </div>
</div>
