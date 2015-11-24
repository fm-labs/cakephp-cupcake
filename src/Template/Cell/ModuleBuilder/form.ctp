<div class="ui form">
    <?= $this->Form->create($form); ?>
    <?= $this->Form->allInputs($inputs, ['legend' => false, 'fieldset' => false]); ?>

    <?= $this->Form->input('_save', ['type' => 'checkbox', 'default' => 0]); ?>

    <?= $this->Form->submit('Save'); ?>
    <?= $this->Form->end(); ?>

</div>