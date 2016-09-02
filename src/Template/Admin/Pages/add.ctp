<?php $this->Html->addCrumb(__d('banana','Pages'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','New {0}', __d('banana','Page'))); ?>
<?php
$this->extend('/Admin/Content/add');
// EXTEND: HEADING
$this->assign('heading', __d('banana','Add {0}', __d('banana','Page')));
?>
<div class="pages">
    <?= $this->Form->create($content, ['class' => 'no-ajax']); ?>
    <div class="users ui segment">
        <div class="ui form">
            <?php
                echo $this->Form->input('parent_id', ['options' => $pagesTree, 'empty' => '- New website root -']);
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
        <?= $this->Form->button(__d('banana','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>
</div>