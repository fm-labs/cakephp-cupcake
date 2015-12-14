<?php $this->Html->addCrumb(__d('banana','Page Layouts')); ?>

<?php $this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Page Layout')), ['action' => 'add'], ['icon' => 'add']); ?>
<div class="pageLayouts index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th><?= $this->Paginator->sort('template') ?></th>
            <th><?= $this->Paginator->sort('is_default') ?></th>
            <th class="actions"><?= __d('banana','Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($pageLayouts as $pageLayout): ?>
        <tr>
            <td><?= $this->Number->format($pageLayout->id) ?></td>
            <td><?= h($pageLayout->name) ?></td>
            <td><?= h($pageLayout->template) ?></td>
            <td><?= h($pageLayout->is_default) ?></td>
            <td class="actions">
                <?php
                $menu = new Backend\Lib\Menu\Menu();
                $menu->add(__d('banana','View'), ['action' => 'view', $pageLayout->id]);

                $dropdown = $menu->add('Dropdown');
                $dropdown->getChildren()->add(
                    __d('banana','Edit'),
                    ['action' => 'edit', $pageLayout->id],
                    ['icon' => 'edit']
                );
                $dropdown->getChildren()->add(
                    __d('banana','Delete'),
                    ['action' => 'delete', $pageLayout->id],
                    ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $pageLayout->id)]
                );
                ?>
                <?= $this->element('Backend.Table/table_row_actions', ['menu' => $menu]); ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
    </table>
    <?= $this->element('Backend.Pagination/default'); ?>
</div>
