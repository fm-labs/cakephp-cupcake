<?php $this->Html->addCrumb(__d('banana','Content Modules'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($contentModule->id); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __d('banana','Edit {0}', __d('banana','Content Module')),
                ['action' => 'edit', $contentModule->id],
                ['class' => 'item', 'icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __d('banana','Delete {0}', __d('banana','Content Module')),
                ['action' => 'delete', $contentModule->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $contentModule->id)]) ?>

            <?= $this->Ui->link(
                __d('banana','List {0}', __d('banana','Content Modules')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __d('banana','New {0}', __d('banana','Content Module')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui item dropdown">
                <div class="menu">
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

<div class="contentModules view">
    <h2 class="ui top attached header">
        <?= h($contentModule->id) ?>
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
            <td><?= __d('banana','Refscope') ?></td>
            <td><?= h($contentModule->refscope) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Module') ?></td>
            <td><?= $contentModule->has('module') ? $this->Html->link($contentModule->module->name, ['controller' => 'Modules', 'action' => 'view', $contentModule->module->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Section') ?></td>
            <td><?= h($contentModule->section) ?></td>
        </tr>


        <tr>
            <td><?= __d('banana','Id') ?></td>
            <td><?= $this->Number->format($contentModule->id) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Refid') ?></td>
            <td><?= $this->Number->format($contentModule->refid) ?></td>
        </tr>

    </table>
</div>
