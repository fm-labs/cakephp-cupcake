<?php $this->Breadcrumbs->add(__('Attribute Sets'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('New {0}', __('Attribute Set'))); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Attribute Sets')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Add {0}', __('Attribute Set')) ?>
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