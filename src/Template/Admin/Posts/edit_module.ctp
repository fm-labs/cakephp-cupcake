<?php $this->Html->addCrumb(__d('banana','Pages'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','Edit {0}', __d('banana','Content Module'))); ?>
<?php $this->extend('/Admin/Content/edit_module'); ?>