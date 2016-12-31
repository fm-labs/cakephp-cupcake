<?php $this->Breadcrumbs->add(__('Attributes'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('New {0}', __('Attribute'))); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Attributes')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Add {0}', __('Attribute')) ?>
    </h2>
    <?= $this->Form->create($attribute, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                echo $this->Form->input('name');
                echo $this->Form->input('title');
                echo $this->Form->input('type');
                echo $this->Form->input('is_required');
                echo $this->Form->input('is_searchable');
                echo $this->Form->input('is_filterable');
                echo $this->Form->input('is_protected');
                echo $this->Form->input('ref');
        ?>
        </div>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>