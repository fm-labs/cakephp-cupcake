<?php $this->Html->addCrumb(__d('banana','Modules'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($module->name); ?>
<?= $this->Toolbar->addLink(
    __d('banana','Edit {0}', __d('banana','Module')),
    ['action' => 'edit', $module->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addPostLink(
    __d('banana','Delete {0}', __d('banana','Module')),
    ['action' => 'delete', $module->id],
    ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $module->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('banana','List {0}', __d('banana','Modules')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('banana','New {0}', __d('banana','Module')),
    ['action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __d('banana','Preview {0}', __d('banana','Module')),
    ['action' => 'preview', $module->id],
    ['icon' => 'plus', 'target' => 'preview']
) ?>

<div class="modules view">
    <h2 class="ui top attached header">
        <?= h($module->name) ?>
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
            <td><?= h($module->name) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Title') ?></td>
            <td><?= h($module->title) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Path') ?></td>
            <td><?= h($module->path) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Entity Class') ?></td>
            <td><?= h(get_class($module)) ?></td>
        </tr>


        <tr>
            <td><?= __d('banana','Id') ?></td>
            <td><?= $this->Number->format($module->id) ?></td>
        </tr>


        <tr class="text">

            <td><?= __d('banana','Params') ?></td>
            <td><?= $this->Text->autoParagraph(h($module->params)); ?></td>
        </tr>

        <tr class="text">
            <td><?= __d('banana','Params ARR') ?></td>
            <td><?= debug($module->params_arr); ?></td>
        </tr>
    </table>

    <?php debug($module); ?>
</div>
