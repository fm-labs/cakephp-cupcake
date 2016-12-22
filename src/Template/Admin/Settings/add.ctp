<?php $this->Breadcrumbs->add(__d('banana','Settings'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('banana','New {0}', __d('banana','Setting'))); ?>
<?php $this->assign('title', __('Settings')); ?>
<?php $this->assign('heading', __d('banana','Add {0}', __d('banana','Setting'))); ?>
<div class="settings form">
    <?= $this->Form->create($setting, ['class' => 'setting']); ?>
    <?php
    echo $this->Form->input('scope');
    echo $this->Form->input('key');
    echo $this->Form->input('title');
    echo $this->Form->input('description');
    echo $this->Form->input('value_type', ['default' => 'string']);
    echo $this->Form->input('value');
    echo $this->Form->input('is_required');
    ?>
    <?= $this->Form->button(__d('banana','Submit')) ?>
    <?= $this->Form->end() ?>

</div>