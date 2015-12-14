<?php $this->Html->addCrumb(__d('banana','Pages')); ?>
<?php $this->extend('/Admin/Content/index'); ?>
<?php
// TOOLBAR
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Page')), ['action' => 'add'], ['icon' => 'add']);
$this->Toolbar->addLink(__d('banana','Repair'), ['action' => 'repair'], ['icon' => 'configure']);

// HEADING
$this->assign('heading', __d('banana','Pages'));

// CONTENT
?>
<div class="pages index">
    <table class="ui sortable compact table" data-sort-url="<?= $this->Url->build(['action' => 'tree_sort']) ?>">
        <thead>
        <tr>
            <th><?= h('id') ?></th>
            <th><?= h('title') ?></th>
            <th><?= h('type') ?></th>
            <th><?= h('url') ?></th>
            <th class="actions"><?= __d('banana','Actions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($contents as $content): ?>
            <tr data-id="<?= h($content->id) ?>">
                <td><?= h($content->id); ?></td>
                <td><?= $this->Html->link($pagesTree[$content->id], ['action' => 'edit', $content->id]); ?></td>
                <td><?= h($content->type); ?></td>
                <td><?= $this->Url->build($content->url); ?></td>
                <td class="actions">
                    <div class="ui basic tiny buttons">


                        <div class="ui button">
                            <?= $this->Html->link(__d('banana','View'), ['action' => 'view', $content->id]) ?>
                        </div>
                        <div class="ui floating dropdown icon button">
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <?= $this->Ui->link(
                                    __d('banana','Preview'),
                                    ['action' => 'preview', $content->id],
                                    ['class' => 'item', 'icon' => 'view']
                                ) ?>
                                <?= $this->Ui->link(
                                    __d('banana','Duplicate'),
                                    ['action' => 'duplicate', $content->id],
                                    ['class' => 'item', 'icon' => 'edit']
                                ) ?>
                                <?= $this->Ui->link(
                                    __d('banana','Move Up'),
                                    ['action' => 'moveUp', $content->id],
                                    ['class' => 'item', 'icon' => 'arrow up']
                                ) ?>
                                <?= $this->Ui->link(
                                    __d('banana','Move Down'),
                                    ['action' => 'moveDown', $content->id],
                                    ['class' => 'item', 'icon' => 'arrow down']
                                ) ?>
                                <?= $this->Ui->deleteLink(
                                    __d('banana','Delete'),
                                    ['action' => 'delete', $content->id],
                                    ['class' => 'item action-delete', 'icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $content->id)]
                                ) ?>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php debug($pagesTree); ?>
</div>
<?php //echo $this->Html->script('Tree.tree-tablesort', ['block' => 'script-bottom', 'inline' => false]); ?>

<div id="modal-action-delete" class="ui basic modal">
    <i class="close icon"></i>
    <div class="header">
        Archive Old Messages
    </div>
    <div class="image content">
        <div class="image">
            <i class="archive icon"></i>
        </div>
        <div class="description">
            <p>Your inbox is getting full, would you like us to enable automatic archiving of old messages?</p>
        </div>
    </div>
    <div class="actions">
        <div class="two fluid ui inverted buttons">
            <div class="ui red basic inverted button">
                <i class="remove icon"></i>
                No
            </div>
            <div class="ui green basic inverted button">
                <i class="checkmark icon"></i>
                Yes
            </div>
        </div>
    </div>
</div>
<?php $this->append('script-bottom'); ?>
<script>
$(document).ready(function() {
    $('.action-delete').click(function() {
        $('#modal-action-delete').modal('show');
    });
});
</script>
<?php $this->end(); ?>