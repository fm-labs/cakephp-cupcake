<?php $this->loadHelper('Backend.Tabs'); ?>
<?php $this->Html->addCrumb(__d('banana','Galleries'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','Edit {0}', __d('banana','Gallery'))); ?>
<?= $this->Toolbar->addPostLink(
    __d('banana','Delete'),
    ['action' => 'delete', $gallery->id],
    ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $gallery->id)]
)
?>
<?= $this->Toolbar->addLink(
    __d('banana','List {0}', __d('banana','Galleries')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>


<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-image"></i>
        <strong><?= __('Edit {0}', __('Gallery')); ?></strong>
        <?= h($gallery->title); ?>
    </div>
    <div class="panel-body">

        <div class="form">
            <?= $this->Form->create($gallery, ['class' => 'no-ajax']); ?>
            <div class="ui form">
                <?php
                echo $this->Form->input('parent_id', ['empty' => true]);
                echo $this->Form->input('title');
                echo $this->Form->input('inherit_desc');
                echo $this->Form->input('desc_html', [
                    'type' => 'htmleditor',
                    'editor' => [
                        'image_list_url' => '@Banana.HtmlEditor.default.imageList',
                        'link_list_url' => '@Banana.HtmlEditor.default.linkList'
                    ]
                ]);
                echo $this->Form->input('view_template');
                echo $this->Form->input('source', ['empty' => true]);
                echo $this->Form->input('source_folder', ['empty' => true]);
                ?>
            </div>
            <?= $this->Form->button(__d('banana','Submit')) ?>
            <?= $this->Form->end() ?>

        </div>

    </div>
</div>

