<div class="module ui form">
    <?= $this->Form->create($module); ?>
    <?= $this->Form->input('id'); ?>
    <?= $this->Form->input('path'); ?>
    <?= $this->Form->input('name'); ?>


    <?= $this->Form->input('source', ['options' => $sources]); ?>
    <?= $this->Form->input('media_config'); ?>
    <?= $this->Form->input('media_folder'); ?>
    <?= $this->Form->input('media_files'); ?>

    <div class="ui hidden divider"></div>
    <?= $this->Form->input('_save', ['type' => 'checkbox', 'default' => 0]); ?>
    <?= $this->Form->submit(); ?>
    <?= $this->Form->end(); ?>
</div>
