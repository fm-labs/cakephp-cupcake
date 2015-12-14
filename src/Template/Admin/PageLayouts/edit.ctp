<?php $this->Html->addCrumb(__d('banana','Page Layouts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','Edit {0}', __d('banana','Page Layout'))); ?>
<?= $this->Toolbar->addPostLink(
    __d('banana','Delete'),
    ['action' => 'delete', $pageLayout->id],
    ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $pageLayout->id)]
)
?>
<?= $this->Toolbar->addLink(
    __d('banana','List {0}', __d('banana','Page Layouts')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('banana','Edit {0}', __d('banana','Page Layout')) ?>
    </h2>
    <?= $this->Form->create($pageLayout); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('name');
                echo $this->Form->input('template');
                echo $this->Form->input('sections');
                echo $this->Form->input('is_default');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('banana','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>