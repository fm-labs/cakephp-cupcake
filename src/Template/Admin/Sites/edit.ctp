<?php $this->Html->addCrumb(__('Sites'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Site'))); ?>
<?= $this->Toolbar->addPostLink(
    __('Delete'),
    ['action' => 'delete', $site->id],
    ['data-icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $site->id)]
)
?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Sites')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Parent Sites')),
    ['controller' => 'Sites', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __('New {0}', __('Parent Site')),
    ['controller' => 'Sites', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Edit {0}', __('Site')) ?>
    </h2>
    <?= $this->Form->create($site, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                    echo $this->Form->input('parent_id', ['options' => $parentSites, 'empty' => true]);
                echo $this->Form->input('alias');
                echo $this->Form->input('title');
                echo $this->Form->input('hostname');
        ?>
        </div>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>