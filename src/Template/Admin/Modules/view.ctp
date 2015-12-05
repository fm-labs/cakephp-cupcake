<?php $this->Html->addCrumb(__('Modules'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($module->name); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Module')),
    ['action' => 'edit', $module->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addPostLink(
    __('Delete {0}', __('Module')),
    ['action' => 'delete', $module->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $module->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Modules')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Module')),
    ['action' => 'add'],
    ['icon' => 'add']
) ?>
<?= $this->Toolbar->addLink(
    __('Preview {0}', __('Module')),
    ['action' => 'preview', $module->id],
    ['icon' => 'add', 'target' => 'preview']
) ?>

<div class="modules view">
    <h2 class="ui top attached header">
        <?= h($module->name) ?>
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
            <td><?= h($module->name) ?></td>
        </tr>
        <tr>
            <td><?= __('Title') ?></td>
            <td><?= h($module->title) ?></td>
        </tr>
        <tr>
            <td><?= __('Path') ?></td>
            <td><?= h($module->path) ?></td>
        </tr>
        <tr>
            <td><?= __('Entity Class') ?></td>
            <td><?= h(get_class($module)) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($module->id) ?></td>
        </tr>


        <tr class="text">

            <td><?= __('Params') ?></td>
            <td><?= $this->Text->autoParagraph(h($module->params)); ?></td>
        </tr>

        <tr class="text">
            <td><?= __('Params ARR') ?></td>
            <td><?= debug($module->params_arr); ?></td>
        </tr>
    </table>

    <?php debug($module); ?>
</div>
