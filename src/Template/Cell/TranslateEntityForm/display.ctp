<?php $this->loadHelper('Form'); ?>
<?= $this->Form->create(null); ?>
<?= $this->Form->fieldsetStart(__d('shop','Languages')); ?>
<?php foreach((array) $this->get('locales') as $_locale => $_localeName): ?>
    <?= $this->Html->link($_localeName, ['action' => 'edit', $this->get('entity')->id, 'locale' => $_locale], ['data-locale' => $_locale]) ?>
<?php endforeach; ?>
<?= $this->Form->fieldsetEnd(); ?>
<?= $this->Form->end(); ?>
