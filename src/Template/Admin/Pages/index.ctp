<?php $this->Html->addCrumb(__('Pages')); ?>
<?php $this->extend('/Admin/Content/index'); ?>
<?php
// TOOLBAR
$this->Toolbar->addLink(__('New {0}', __('Page')), ['action' => 'add'], ['icon' => 'add']);

// HEADING
$this->assign('heading', __('Pages'));

// CONTENT
?>
<div class="pages index">
    <table class="ui sortable table" data-sort-url="<?= $this->Url->build(['action' => 'tree_sort']) ?>">
        <tbody>
        <?php foreach ($contents as $content): ?>
            <tr data-id="<?= h($content->id) ?>">
                <td><?= $this->Html->link($treeList[$content->id], ['action' => 'edit', $content->id]); ?></td>
                <td><?= h($content->type); ?></td>
                <td><?= h($content->layout_template); ?></td>
                <td><?= $this->Url->build($content->url); ?></td>
                <td class="actions">
                    <div class="ui basic small buttons">


                        <div class="ui button">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $content->id]) ?>
                        </div>
                        <div class="ui floating dropdown icon button">
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <?= $this->Ui->link(
                                    __('Duplicate'),
                                    ['action' => 'duplicate', $content->id],
                                    ['class' => 'item', 'icon' => 'edit']
                                ) ?>
                                <?= $this->Ui->deleteLink(
                                    __('Delete'),
                                    ['action' => 'delete', $content->id],
                                    ['class' => 'item action-delete', 'icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $content->id)]
                                ) ?>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php debug($treeList); ?>
</div>
<?php echo $this->Html->script('Tree.tree-tablesort', ['block' => 'script-bottom', 'inline' => false]); ?>

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