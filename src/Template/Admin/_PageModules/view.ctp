<?php $this->Html->addCrumb(__('Page Modules'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($pageModule->id); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('Edit {0}', __('Page Module')),
                ['action' => 'edit', $pageModule->id],
                ['class' => 'item', 'icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __('Delete {0}', __('Page Module')),
                ['action' => 'delete', $pageModule->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $pageModule->id)]) ?>

            <?= $this->Ui->link(
                __('List {0}', __('Page Modules')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __('New {0}', __('Page Module')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui item dropdown">
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

<div class="pageModules view">
    <h2 class="ui top attached header">
        <?= h($pageModule->id) ?>
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
            <td><?= __('Page') ?></td>
            <td><?= $pageModule->has('page') ? $this->Html->link($pageModule->page->title, ['controller' => 'Pages', 'action' => 'view', $pageModule->page->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __('Module') ?></td>
            <td><?= $pageModule->has('module') ? $this->Html->link($pageModule->module->name, ['controller' => 'Modules', 'action' => 'view', $pageModule->module->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __('Section') ?></td>
            <td><?= h($pageModule->section) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($pageModule->id) ?></td>
        </tr>

    </table>
</div>
