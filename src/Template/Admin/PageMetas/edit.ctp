<?php $this->Html->addCrumb(__('Page Metas'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Page Meta'))); ?>
<?= $this->Toolbar->addPostLink(
    __('Delete'),
    ['action' => 'delete', $pageMeta->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $pageMeta->id)]
)
?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Page Metas')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Edit {0}', __('Page Meta')) ?>
    </h2>
    <?= $this->Form->create($pageMeta); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                echo $this->Form->hidden('model');
                echo $this->Form->hidden('foreignKey');
                echo $this->Form->input('title');
                echo $this->Form->input('description');
                echo $this->Form->input('keywords');
                echo $this->Form->input('robots', ['options' => $robots, 'empty' => __('-- Select --')]);
                echo $this->Form->input('lang');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>