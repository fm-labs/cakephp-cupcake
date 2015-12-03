<?php $this->Html->addCrumb(__('Posts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('New {0}', __('Post'))); ?>
<?php
$this->extend('/Admin/Content/add');
// EXTEND: HEADING
$this->assign('heading', __('Add {0}', __('Post')));
?>
<div class="posts">
    <?php var_dump($content->errors()); ?>
    <?= $this->Form->create($content); ?>
    <div class="users ui top attached segment">
        <div class="ui form">
        <?php
            echo $this->Form->input('title');
            echo $this->Form->hidden('refscope');
            echo $this->Form->hidden('refid');
            echo $this->Form->hidden('slug');
            echo $this->Form->hidden('is_published', ['value' => 0]);
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__('Continue')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>