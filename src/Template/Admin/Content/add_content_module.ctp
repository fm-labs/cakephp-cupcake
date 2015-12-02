<div class="ui form">
    <?= $this->Form->create($contentModule, ['url' => ['action' => 'addContentModule', $contentModule->refid]]); ?>
    <?= $this->Form->input('id'); ?>
    <?= $this->Form->input('refscope', ['value' => 'Banana.Pages']); ?>
    <?= $this->Form->input('refid', []); ?>
    <?= $this->Form->input('module_id', ['options' => $availableModules]); ?>
    <?= $this->Form->input('section', ['options' => $sections]); ?>
    <?= $this->Form->submit('Add content module'); ?>
    <?= $this->Form->end(); ?>
</div>

<?php debug($availableModules); ?>