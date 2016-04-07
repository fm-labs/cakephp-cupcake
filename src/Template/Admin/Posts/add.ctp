<?php $this->Html->addCrumb(__d('banana','Posts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','New {0}', __d('banana','Post'))); ?>
<?php
$this->extend('/Admin/Content/add');
// EXTEND: HEADING
$this->assign('heading', __d('banana','Add {0}', __d('banana','Post')));
?>
<div class="posts">
    <?php var_dump($content->errors()); ?>
    <?= $this->Form->create($content, ['class' => 'no-ajax']); ?>
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
        <?= $this->Form->button(__d('banana','Continue')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>