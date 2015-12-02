<?php $this->Html->addCrumb('Module Builder', ['action' => 'index']) ?>

<div class="form">
    <h3>Add Module for Page: <?= h($content->title); ?> [<?= h($modulePath); ?>] in [<?= h($section); ?>]</h3>
    <?php
    echo $this->Form->create($moduleForm, ['class' => 'ui form']);
    //echo $this->Form->input('_path', ['value' => $modulePath]);
    echo $this->Form->allInputs($moduleFormInputs, ['legend' => false, 'fieldset' => false]);
    echo $this->Form->button('Save');
    echo $this->Form->end();
    ?>

    <?= debug($moduleParams); ?>
    <?= debug(json_encode($moduleParams, JSON_PRETTY_PRINT)); ?>

    <?php
    //echo $this->module($modulePath, $moduleParams);
    ?>

    <?php
    //debug($moduleClass);
    //debug($moduleSchema);
    ?>
</div>