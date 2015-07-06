<?php $this->Html->addCrumb(__('Pages')); ?>
<?php $this->extend('/Admin/Content/index'); ?>
<?php
// EXTEND: TOOLBAR
$toolbarMenu = [
    'new' => [
        'title' => __('New {0}', __('Page')),
        'url' => ['action' => 'add'],
        'attr' => ['icon' => 'add']
    ]
];

$this->set('toolbarMenu', $toolbarMenu);

// EXTEND: HEADING
$this->assign('heading', __('Pages'));
?>
<div class="pages index">
    <table class="ui table">
        <?php foreach ($contents as $content): ?>
            <tr>
                <td><?= $this->Html->link($treeList[$content->id], ['action' => 'edit', $content->id]); ?></td>
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
                                <?= $this->Ui->postLink(
                                    __('Delete'),
                                    ['action' => 'delete', $content->id],
                                    ['class' => 'item', 'icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $content->id)]
                                ) ?>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
