<?php $this->Html->addCrumb(__('Posts')); ?>
<?php $this->extend('/Admin/Content/index'); ?>
<?php
// EXTEND: TOOLBAR
$this->Toolbar->addLink(__('New {0}', __('Post')), ['action' => 'add'], ['icon' => 'add']);

// EXTEND: HEADING
$this->assign('heading', __('Posts'));
?>
<div class="posts index">
    <table class="ui table compact striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('refscope') ?></th>
            <th><?= $this->Paginator->sort('refid') ?></th>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('is_published') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($contents as $content): ?>
        <tr>
            <td><?= $this->Number->format($content->id) ?></td>
            <td><?= h($content->refscope) ?></td>
            <td><?= h($content->refid) ?></td>
            <td><?= $this->Html->link($content->title, ['action' => 'edit', $content->id]) ?></td>
            <td><?= h($content->is_published) ?></td>
            <td class="actions">
                <div class="ui basic tiny buttons">
                    <div class="ui button">
                        <?= $this->Html->link(__('Preview'), ['action' => 'preview', $content->id], ['target' => 'preview']) ?>
                    </div>
                    <div class="ui floating dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <?= $this->Ui->link(
                                __('View'),
                                ['action' => 'view', $content->id],
                                ['class' => 'item', 'icon' => 'view']
                            ) ?>
                            <?= $this->Ui->link(
                                __('Edit'),
                                ['action' => 'edit', $content->id],
                                ['class' => 'item', 'icon' => 'edit']
                            ) ?>
                            <?= $this->Ui->postLink(
                                __('Delete'),
                                ['action' => 'delete', $content->id],
                                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $content->id)]
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
