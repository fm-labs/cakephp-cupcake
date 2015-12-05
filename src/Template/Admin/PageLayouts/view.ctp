<?php $this->Html->addCrumb(__('Page Layouts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($pageLayout->name); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Page Layout')),
    ['action' => 'edit', $pageLayout->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('Page Layout')),
    ['action' => 'delete', $pageLayout->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $pageLayout->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Page Layouts')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Page Layout')),
    ['action' => 'add'],
    ['icon' => 'add']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="pageLayouts view">
    <h2 class="ui header">
        <?= h($pageLayout->name) ?>
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
            <td><?= h($pageLayout->name) ?></td>
        </tr>
        <tr>
            <td><?= __('Template') ?></td>
            <td><?= h($pageLayout->template) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($pageLayout->id) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __('Is Default') ?></td>
            <td><?= $pageLayout->is_default ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr class="text">
            <td><?= __('Sections') ?></td>
            <td><?= $this->Text->autoParagraph(h($pageLayout->sections)); ?></td>
        </tr>
    </table>
</div>
