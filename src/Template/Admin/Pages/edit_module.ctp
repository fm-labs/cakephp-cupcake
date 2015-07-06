<?php $this->Html->addCrumb(__('Pages'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Content Module'))); ?>
<?php $this->extend('/Admin/Content/edit_module'); ?>