<?php $this->Breadcrumbs->add(__d('banana','Settings'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('banana','Edit {0}', __d('banana','Setting'))); ?>
<?php $this->assign('title', __('Settings')); ?>
<?php $this->assign('heading', __d('banana','Edit {0}', __d('banana','Setting'))); ?>
<div class="settings form">
    <?= $this->Form->create($setting, ['class' => 'setting']); ?>
    <?php
    echo $this->Form->input('scope');
    echo $this->Form->input('key');
    echo $this->Form->input('value');
    ?>
    <?= $this->Form->button(__d('banana','Submit')) ?>
    <?= $this->Form->end() ?>

</div>