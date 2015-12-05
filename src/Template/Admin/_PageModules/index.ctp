<?php $this->Html->addCrumb(__('Page Modules')); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('New {0}', __('Page Module')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui dropdown item">
                <i class="dropdown icon"></i>
                <i class="setting icon"></i>Actions
                <div class="menu">
                    <?= $this->Ui->link(
                        __('List {0}', __('Pages')),
                        ['controller' => 'Pages', 'action' => 'index'],
                        ['class' => 'item', 'icon' => 'list']
                    ) ?>

                    <?= $this->Ui->link(
                        __('New {0}', __('Page')),
                        ['controller' => 'Pages', 'action' => 'add'],
                        ['class' => 'item', 'icon' => 'add']
                    ) ?>
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

<div class="pageModules index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('page_id') ?></th>
            <th><?= $this->Paginator->sort('module_id') ?></th>
            <th><?= $this->Paginator->sort('section') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($pageModules as $pageModule): ?>
        <tr>
            <td><?= $this->Number->format($pageModule->id) ?></td>
            <td>
                <?= $pageModule->has('page') ? $this->Html->link($pageModule->page->title, ['controller' => 'Pages', 'action' => 'view', $pageModule->page->id]) : '' ?>
            </td>
            <td>
                <?= $pageModule->has('module') ? $this->Html->link($pageModule->module->name, ['controller' => 'Modules', 'action' => 'view', $pageModule->module->id]) : '' ?>
            </td>
            <td><?= h($pageModule->section) ?></td>
            <td class="actions">
                <div class="ui basic small buttons">
                    <div class="ui button">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $pageModule->id]) ?>
                    </div>
                    <div class="ui floating dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <?= $this->Ui->link(
                                __('Edit'),
                                ['action' => 'edit', $pageModule->id],
                                ['class' => 'item', 'icon' => 'edit']
                            ) ?>
                            <?= $this->Ui->postLink(
                                __('Delete'),
                                ['action' => 'delete', $pageModule->id],
                                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $pageModule->id)]
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
