<?php $this->Html->addCrumb(__d('banana','Page Layouts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($pageLayout->name); ?>
<?= $this->Toolbar->addLink(
    __d('banana','Edit {0}', __d('banana','Page Layout')),
    ['action' => 'edit', $pageLayout->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('banana','Delete {0}', __d('banana','Page Layout')),
    ['action' => 'delete', $pageLayout->id],
    ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $pageLayout->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('banana','List {0}', __d('banana','Page Layouts')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('banana','New {0}', __d('banana','Page Layout')),
    ['action' => 'add'],
    ['icon' => 'add']
) ?>
<?= $this->Toolbar->startGroup(__d('banana','More')); ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="pageLayouts view">
    <h2 class="ui header">
        <?= h($pageLayout->name) ?>
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
            <td><?= __d('banana','Name') ?></td>
            <td><?= h($pageLayout->name) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Template') ?></td>
            <td><?= h($pageLayout->template) ?></td>
        </tr>


        <tr>
            <td><?= __d('banana','Id') ?></td>
            <td><?= $this->Number->format($pageLayout->id) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('banana','Is Default') ?></td>
            <td><?= $pageLayout->is_default ? __d('banana','Yes') : __d('banana','No'); ?></td>
        </tr>
        <tr class="text">
            <td><?= __d('banana','Sections') ?></td>
            <td><?= $this->Text->autoParagraph(h($pageLayout->sections)); ?></td>
        </tr>
    </table>
</div>
