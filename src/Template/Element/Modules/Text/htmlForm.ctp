<div class="ui form">
    <?= $this->Form->create($module); ?>
    <?= $this->Form->input('textHtml', ['type' => 'htmleditor']); ?>

    <?= $this->Form->submit('Save'); ?>
    <?= $this->Form->end(); ?>
</div>