<?php $this->Breadcrumbs->add(__('Sites'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('New {0}', __('Site'))); ?>
<?php $this->Toolbar->addLink(
    __('List {0}', __('Sites')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->addLink(
    __('List {0}', __('Parent Sites')),
    ['controller' => 'Sites', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __('New {0}', __('Parent Site')),
    ['controller' => 'Sites', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Add {0}', __('Site')) ?>
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