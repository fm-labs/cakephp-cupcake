<?php $this->Html->addCrumb(__d('banana','Galleries'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','New {0}', __d('banana','Gallery Item'))); ?>
<div class="posts">
    <?= $this->Form->create($item); ?>
    <div class="users ui top attached segment">
        <div class="ui form">
        <?php
            echo $this->Form->input('refscope', ['default' => 'Banana.Galleries']);
            echo $this->Form->input('refid');
            echo $this->Form->input('title');
            echo $this->Form->input('image_file', ['type' => 'imageselect', 'options' => '@default']);
            echo $this->Form->input('body_html', ['type' => 'htmleditor']);
            echo $this->Form->hidden('is_published', ['default' => 1]);
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('banana','Continue')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>