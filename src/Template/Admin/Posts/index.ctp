<?php $this->Html->addCrumb(__('Posts')); ?>
<?php $this->extend('/Admin/Content/index'); ?>
<?php
// EXTEND: TOOLBAR
$toolbarMenu = [
    'new' => [
        'title' => __('New {0}', __('Post')),
        'url' => ['action' => 'add'],
        'attr' => ['icon' => 'add']
    ]
];

$this->set('toolbarMenu', $toolbarMenu);

// EXTEND: HEADING
$this->assign('heading', __('Posts'));
?>
<div class="posts index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('is_published') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($contents as $content): ?>
        <tr>
            <td><?= $this->Number->format($content->id) ?></td>
            <td><?= $this->Html->link($content->title, ['action' => 'edit', $content->id]) ?></td>
            <td><?= h($content->is_published) ?></td>
            <td class="actions">
                <div class="ui basic small buttons">
                    <div class="ui button">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $content->id]) ?>
                    </div>
                    <div class="ui floating dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <?= $this->Ui->link(
                                __('Edit'),
                                ['action' => 'edit', $content->id],
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
    </tbody>
    </table>
</div>
