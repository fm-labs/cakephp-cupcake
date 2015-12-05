<?php $this->Html->addCrumb(__('Page Layouts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Page Layout'))); ?>
<?= $this->Toolbar->addPostLink(
    __('Delete'),
    ['action' => 'delete', $pageLayout->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $pageLayout->id)]
)
?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Page Layouts')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Edit {0}', __('Page Layout')) ?>
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
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>