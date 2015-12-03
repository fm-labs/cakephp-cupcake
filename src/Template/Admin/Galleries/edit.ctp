<?php $this->Html->addCrumb(__('Galleries'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Gallery'))); ?>
<?= $this->Toolbar->addPostLink(
    __('Delete'),
    ['action' => 'delete', $gallery->id],
    ['icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $gallery->id)]
)
?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Galleries')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Posts')),
    ['controller' => 'Posts', 'action' => 'index'],
    ['icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __('New {0}', __('Post')),
    ['controller' => 'Posts', 'action' => 'add'],
    ['icon' => 'add']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Edit {0}', __('Gallery')) ?>
    </h2>
    <?= $this->Form->create($gallery); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
            echo $this->Form->input('title');
            echo $this->Form->input('desc_html', ['type' => 'htmleditor']);
            echo $this->Form->input('view_template');
            echo $this->Form->input('source');
            echo $this->Form->input('source_folder');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>