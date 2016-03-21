<?= $this->Form->create($pageMeta, ['class' => 'form-ajax']); ?>
<?php
echo $this->Form->hidden('model');
echo $this->Form->hidden('foreignKey');
echo $this->Form->input('title');
echo $this->Form->input('description');
echo $this->Form->input('keywords');
echo $this->Form->input('robots');
echo $this->Form->input('lang');
?>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end(); ?>
