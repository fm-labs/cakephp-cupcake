<?php $this->Html->addCrumb('Module Builder', ['action' => 'index']) ?>

<div class="form">
    <h1>Module Builder: <?= h($modulePath); ?>Widget</h1>
    <div class="ui divider"></div>

    <div class="ui two column grid">
        <div class="column">
            <h3>Add Module: <?= h($modulePath); ?></h3>
            <?php
            echo $this->Form->create($moduleForm, ['class' => 'ui form']);
            //echo $this->Form->input('_path', ['value' => $modulePath]);
            echo $this->Form->allInputs($moduleFormInputs, ['legend' => false, 'fieldset' => false]);
            echo $this->Form->button('Save');
            echo $this->Form->end();
            ?>
        </div>
        <div class="column">
            <?= $this->Html->link(
                'Open in Iframe',
                ['action' => 'module_preview', 'path' => $modulePath, 'params' => base64_encode(json_encode($moduleParams))],
                ['target' => '_blank']
            ); ?>
            <?php
            echo $this->module($modulePath, $moduleParams);
            ?>
        </div>
    </div>
    <div class="ui divider"></div>
    <div class="ui two column grid">
        <div class="column">
            <pre><?= var_dump($moduleParams); ?></pre>

            <pre><?= h(json_encode($moduleParams, JSON_PRETTY_PRINT)); ?></pre>
        </div>
        <div class="column">
            <?php
            echo $this->Form->create($module, [
                'class' => 'ui form',
                'url' => ['controller' => 'Modules', 'action' => 'add']
            ]);
            echo $this->Form->input('name');
            echo $this->Form->input('title');
            echo $this->Form->input('path', ['value' => $modulePath]);
            echo $this->Form->input('params', ['value' => json_encode($moduleParams)]);
            echo $this->Form->button('Submit');
            echo $this->Form->end();
            ?>
        </div>
    </div>

    <div class="ui divider"></div>
    <div class="ui two column grid">
        <div class="column">
        </div>
        <div class="column">
        </div>
    </div>

</div>