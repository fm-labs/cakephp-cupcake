<?php $this->Html->addCrumb(__d('banana','Posts')); ?>
<?php $this->extend('/Admin/Content/index'); ?>
<?php
// EXTEND: TOOLBAR
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Post')), ['action' => 'add'], ['icon' => 'add']);

// EXTEND: HEADING
$this->assign('heading', __d('banana','Posts'));
?>
<div class="posts index">

    <!-- Quick Search -->
    <div class="ui segment">
        <div class="ui form">
            <?= $this->Form->create(null, ['id' => 'quickfinder', 'action' => 'quick']); ?>
            <?= $this->Form->input('post_id', [
                'options' => $postsList,
                'label' => false,
                'empty' => '- Quick Search -'
            ]); ?>
            <?= $this->Form->button('Go'); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>

    <table class="ui table compact striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('is_published') ?></th>
            <th><?= $this->Paginator->sort('refscope') ?></th>
            <th><?= $this->Paginator->sort('refid') ?></th>
            <th class="actions"><?= __d('banana','Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($contents as $content): ?>
        <tr>
            <td><?= $this->Number->format($content->id) ?></td>
            <td><?= h($content->created) ?></td>
            <td><?= $this->Html->link($content->title, ['action' => 'edit', $content->id]) ?></td>
            <td><?= h($content->is_published) ?></td>
            <td><?= h($content->refscope) ?></td>
            <td><?= h($content->refid) ?></td>
            <td class="actions">
                <div class="ui basic tiny buttons">
                    <div class="ui button">
                        <?= $this->Html->link(__d('banana','Preview'), ['action' => 'preview', $content->id], ['target' => 'preview']) ?>
                    </div>
                    <div class="ui floating dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <?= $this->Ui->link(
                                __d('banana','View'),
                                ['action' => 'view', $content->id],
                                ['class' => 'item', 'icon' => 'view']
                            ) ?>
                            <?= $this->Ui->link(
                                __d('banana','Edit'),
                                ['action' => 'edit', $content->id],
                                ['class' => 'item', 'icon' => 'edit']
                            ) ?>
                            <?= $this->Ui->postLink(
                                __d('banana','Delete'),
                                ['action' => 'delete', $content->id],
                                ['class' => 'item', 'icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $content->id)]
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
