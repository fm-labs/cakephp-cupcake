<div class="ui form">
    <?= $this->Form->create($module); ?>
    <?= $this->Form->input('id'); ?>
    <?= $this->Form->input('path'); ?>
    <?= $this->Form->input('name'); ?>
    <?= $this->Form->input('textHtml', ['type' => 'htmleditor']); ?>

    <div class="ui hidden divider"></div>
    <?= $this->Form->input('_save', ['type' => 'checkbox', 'default' => 0]); ?>

    <?= $this->Form->input('params', ['disabled' => true]); ?>
    <?= $this->Form->submit('Save'); ?>
    <?= $this->Form->end(); ?>

</div>