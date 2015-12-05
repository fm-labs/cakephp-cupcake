<?php $this->Html->addCrumb(__('Modules')); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('New {0}', __('Module')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui dropdown item">
                <i class="dropdown icon"></i>
                <i class="setting icon"></i>Actions
                <div class="menu">
                    <div class="item">No Actions</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="modules index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('path') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($modules as $module): ?>
        <tr>
            <td><?= $this->Number->format($module->id) ?></td>
            <td><?= h($module->name) ?></td>
            <td><?= h($module->title) ?></td>
            <td><?= h($module->path) ?></td>
            <td class="actions">
                <div class="ui basic small buttons">
                    <div class="ui button">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $module->id]) ?>
                    </div>
                    <div class="ui floating dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <?= $this->Ui->link(
                                __('Edit'),
                                ['controller' => 'ModuleBuilder', 'action' => 'edit', $module->id],
                                ['class' => 'item', 'icon' => 'edit']
                            ) ?>
                            <?= $this->Ui->postLink(
                                __('Delete'),
                                ['action' => 'delete', $module->id],
                                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $module->id)]
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
