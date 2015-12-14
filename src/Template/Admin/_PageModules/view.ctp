<?php $this->Html->addCrumb(__d('banana','Page Modules'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($pageModule->id); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __d('banana','Edit {0}', __d('banana','Page Module')),
                ['action' => 'edit', $pageModule->id],
                ['class' => 'item', 'icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __d('banana','Delete {0}', __d('banana','Page Module')),
                ['action' => 'delete', $pageModule->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $pageModule->id)]) ?>

            <?= $this->Ui->link(
                __d('banana','List {0}', __d('banana','Page Modules')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __d('banana','New {0}', __d('banana','Page Module')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui item dropdown">
                <div class="menu">
                    <?= $this->Ui->link(
                        __d('banana','List {0}', __d('banana','Pages')),
                        ['controller' => 'Pages', 'action' => 'index'],
                        ['class' => 'item', 'icon' => 'list']
                    ) ?>
                    <?= $this->Ui->link(
                        __d('banana','New {0}', __d('banana','Page')),
                        ['controller' => 'Pages', 'action' => 'add'],
                        ['class' => 'item', 'icon' => 'add']
                    ) ?>
                    <?= $this->Ui->link(
                        __d('banana','List {0}', __d('banana','Modules')),
                        ['controller' => 'Modules', 'action' => 'index'],
                        ['class' => 'item', 'icon' => 'list']
                    ) ?>
                    <?= $this->Ui->link(
                        __d('banana','New {0}', __d('banana','Module')),
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
            <th><?= __d('banana','Label'); ?></th>
            <th><?= __d('banana','Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __d('banana','Page') ?></td>
            <td><?= $pageModule->has('page') ? $this->Html->link($pageModule->page->title, ['controller' => 'Pages', 'action' => 'view', $pageModule->page->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Module') ?></td>
            <td><?= $pageModule->has('module') ? $this->Html->link($pageModule->module->name, ['controller' => 'Modules', 'action' => 'view', $pageModule->module->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Section') ?></td>
            <td><?= h($pageModule->section) ?></td>
        </tr>


        <tr>
            <td><?= __d('banana','Id') ?></td>
            <td><?= $this->Number->format($pageModule->id) ?></td>
        </tr>

    </table>
</div>
