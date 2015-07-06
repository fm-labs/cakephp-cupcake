<?php $this->Html->addCrumb(__('Pages')); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('New {0}', __('Page')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui dropdown item">
                <i class="dropdown icon"></i>
                <i class="setting icon"></i>Actions
                <div class="menu">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="index">
    <h1>Pages</h1>

    <table class="ui table">
    <?php foreach ($pages as $page): ?>
    <tr>
        <td><?= $this->Html->link($treeList[$page->id], ['action' => 'edit', $page->id]); ?></td>
        <td class="actions">
            <div class="ui basic small buttons">

                <?= $this->Ui->link(
                    __('Edit'),
                    ['action' => 'edit', $page->id],
                    ['class' => 'ui button', 'icon' => 'edit']
                ) ?>

                <div class="ui button">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $page->id]) ?>
                </div>
                <div class="ui floating dropdown icon button">
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <?= $this->Ui->postLink(
                            __('Delete'),
                            ['action' => 'delete', $page->id],
                            ['class' => 'item', 'icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $page->id)]
                        ) ?>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>

    <?php debug($treeList); ?>
    <?php debug($pages->toArray()); ?>
    <?php debug($children->toArray()); ?>
</div>