<?php $this->Html->addCrumb(__('Page Metas'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('New {0}', __('Page Meta'))); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Page Metas')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Add {0}', __('Page Meta')) ?>
    </h2>
    <?= $this->Form->create($pageMeta); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('model');
                echo $this->Form->input('foreignKey');
                echo $this->Form->input('title');
                echo $this->Form->input('description');
                echo $this->Form->input('keywords');
                echo $this->Form->input('robots');
                echo $this->Form->input('lang');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>