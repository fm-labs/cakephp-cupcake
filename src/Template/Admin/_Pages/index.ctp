<?php $this->Html->addCrumb(__d('banana','Pages')); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __d('banana','New {0}', __d('banana','Page')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui dropdown item">
                <i class="dropdown icon"></i>
                <i class="setting icon"></i>Actions
                <div class="menu">
                    <?= $this->Ui->link(
                        __d('banana','List {0}', __d('banana','Page Modules')),
                        ['controller' => 'PageModules', 'action' => 'index'],
                        ['class' => 'item', 'icon' => 'list']
                    ) ?>

                    <?= $this->Ui->link(
                        __d('banana','New {0}', __d('banana','Page Module')),
                        ['controller' => 'PageModules', 'action' => 'add'],
                        ['class' => 'item', 'icon' => 'add']
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="pages index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('lft') ?></th>
            <th><?= $this->Paginator->sort('rght') ?></th>
            <th><?= $this->Paginator->sort('parent_id') ?></th>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('slug') ?></th>
            <th><?= $this->Paginator->sort('layout_template') ?></th>
            <th class="actions"><?= __d('banana','Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($pages as $page): ?>
        <tr>
            <td><?= $this->Number->format($page->id) ?></td>
            <td><?= $this->Number->format($page->lft) ?></td>
            <td><?= $this->Number->format($page->rght) ?></td>
            <td><?= $this->Number->format($page->parent_id) ?></td>
            <td><?= h($page->title) ?></td>
            <td><?= h($page->slug) ?></td>
            <td><?= h($page->layout_template) ?></td>
            <td class="actions">
                <div class="ui basic small buttons">
                    <div class="ui button">
                        <?= $this->Html->link(__d('banana','View'), ['action' => 'view', $page->id]) ?>
                    </div>
                    <div class="ui floating dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <?= $this->Ui->link(
                                __d('banana','Edit'),
                                ['action' => 'edit', $page->id],
                                ['class' => 'item', 'icon' => 'edit']
                            ) ?>
                            <?= $this->Ui->postLink(
                                __d('banana','Delete'),
                                ['action' => 'delete', $page->id],
                                ['class' => 'item', 'icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $page->id)]
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
            <?= $this->Paginator->prev(__d('banana','previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__d('banana','next')) ?>

            <div class="item">
                <?= $this->Paginator->counter() ?>
            </div>
        </div>
    </div>
</div>
