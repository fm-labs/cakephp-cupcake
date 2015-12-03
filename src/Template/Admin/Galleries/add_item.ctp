<?php $this->Html->addCrumb(__('Galleries'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('New {0}', __('Gallery Item'))); ?>
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
        <?= $this->Form->button(__('Continue')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>