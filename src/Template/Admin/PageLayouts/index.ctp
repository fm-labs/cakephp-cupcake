<?php $this->Html->addCrumb(__('Page Layouts')); ?>

<?php $this->Toolbar->addLink(__('New {0}', __('Page Layout')), ['action' => 'add'], ['icon' => 'add']); ?>
<div class="pageLayouts index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th><?= $this->Paginator->sort('template') ?></th>
            <th><?= $this->Paginator->sort('is_default') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
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
                $menu->add(__('View'), ['action' => 'view', $pageLayout->id]);

                $dropdown = $menu->add('Dropdown');
                $dropdown->getChildren()->add(
                    __('Edit'),
                    ['action' => 'edit', $pageLayout->id],
                    ['icon' => 'edit']
                );
                $dropdown->getChildren()->add(
                    __('Delete'),
                    ['action' => 'delete', $pageLayout->id],
                    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $pageLayout->id)]
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
