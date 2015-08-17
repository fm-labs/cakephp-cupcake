<?php $this->Html->addCrumb(__('Pages'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('New {0}', __('Page'))); ?>
<?php
$this->extend('/Admin/Content/add');
// EXTEND: HEADING
$this->assign('heading', __('Add {0}', __('Page')));
?>
<div class="pages">
    <?= $this->Form->create($content); ?>
    <div class="users ui segment">
        <div class="ui form">
            <?php
                echo $this->Form->input('parent_id', ['options' => $treeList, 'empty' => '- No parent -']);
                echo $this->Form->input('title');
                echo $this->Form->hidden('slug');
                echo $this->Form->input('type', ['id' => 'select-type']);
                //echo $this->Form->hidden('is_published');
                //echo $this->Form->hidden('publish_start_date');
                //echo $this->Form->hidden('publish_end_date');
                //echo $this->Form->hidden('parent_id', ['options' => $treeList, 'empty' => '- No parent -']);
                //echo $this->Form->hidden('layout_template');
                //echo $this->Form->hidden('page_template');
            ?>

        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>
</div>