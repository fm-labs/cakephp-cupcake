<?php $this->Breadcrumbs->add(__('Attribute Sets'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('Edit {0}', __('Attribute Set'))); ?>
<?= $this->Toolbar->addPostLink(
    __('Delete'),
    ['action' => 'delete', $attributeSet->id],
    ['data-icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $attributeSet->id)]
)
?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Attribute Sets')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Edit {0}', __('Attribute Set')) ?>
    </h2>
    <?= $this->Form->create($attributeSet, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                echo $this->Form->input('title');
        ?>
        </div>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>