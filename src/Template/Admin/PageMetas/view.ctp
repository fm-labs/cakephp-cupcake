<?php $this->Html->addCrumb(__('Page Metas'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($pageMeta->title); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Page Meta')),
    ['action' => 'edit', $pageMeta->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('Page Meta')),
    ['action' => 'delete', $pageMeta->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $pageMeta->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Page Metas')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Page Meta')),
    ['action' => 'add'],
    ['icon' => 'add']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="pageMetas view">
    <h2 class="ui header">
        <?= h($pageMeta->title) ?>
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
            <td><?= __('Model') ?></td>
            <td><?= h($pageMeta->model) ?></td>
        </tr>
        <tr>
            <td><?= __('Title') ?></td>
            <td><?= h($pageMeta->title) ?></td>
        </tr>
        <tr>
            <td><?= __('Robots') ?></td>
            <td><?= h($pageMeta->robots) ?></td>
        </tr>
        <tr>
            <td><?= __('Lang') ?></td>
            <td><?= h($pageMeta->lang) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($pageMeta->id) ?></td>
        </tr>
        <tr>
            <td><?= __('ForeignKey') ?></td>
            <td><?= $this->Number->format($pageMeta->foreignKey) ?></td>
        </tr>

        <tr class="text">
            <td><?= __('Description') ?></td>
            <td><?= $this->Text->autoParagraph(h($pageMeta->description)); ?></td>
        </tr>
        <tr class="text">
            <td><?= __('Keywords') ?></td>
            <td><?= $this->Text->autoParagraph(h($pageMeta->keywords)); ?></td>
        </tr>
    </table>
</div>
