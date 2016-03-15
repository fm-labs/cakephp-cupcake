<?php $this->Html->addCrumb(__('Page Metas')); ?>

<?php $this->Toolbar->addLink(__('New {0}', __('Page Meta')), ['action' => 'add'], ['icon' => 'add']); ?>
<div class="pageMetas index">
    <table class="ui compact table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('model') ?></th>
            <th><?= $this->Paginator->sort('foreignKey') ?></th>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('robots') ?></th>
            <th><?= $this->Paginator->sort('lang') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($pageMetas as $pageMeta): ?>
        <tr>
            <td><?= $this->Number->format($pageMeta->id) ?></td>
            <td><?= h($pageMeta->model) ?></td>
            <td><?= $this->Number->format($pageMeta->foreignKey) ?></td>
            <td><?= h($pageMeta->title) ?></td>
            <td><?= h($pageMeta->robots) ?></td>
            <td><?= h($pageMeta->lang) ?></td>
            <td class="actions">
                <?php
                $menu = new Backend\Lib\Menu\Menu();
                $menu->add(__('View'), ['action' => 'view', $pageMeta->id]);

                $dropdown = $menu->add('Dropdown');
                $dropdown->getChildren()->add(
                    __('Edit'),
                    ['action' => 'edit', $pageMeta->id],
                    ['icon' => 'edit']
                );
                $dropdown->getChildren()->add(
                    __('Delete'),
                    ['action' => 'delete', $pageMeta->id],
                    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $pageMeta->id)]
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
