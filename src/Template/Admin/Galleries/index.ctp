<?php $this->Html->addCrumb(__('Galleries')); ?>

<?php $this->Toolbar->addLink(__('New {0}', __('Gallery')), ['action' => 'add'], ['icon' => 'add']); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Posts')),
    ['controller' => 'Posts', 'action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Post')),
    ['controller' => 'Posts', 'action' => 'add'],
    ['icon' => 'add']
) ?>
<div class="galleries index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('view_template') ?></th>
            <th><?= $this->Paginator->sort('source') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($galleries as $gallery): ?>
        <tr>
            <td><?= $this->Number->format($gallery->id) ?></td>
            <td><?= $this->Html->link($gallery->title, ['action' => 'view', $gallery->id]) ?></td>
            <td><?= h($gallery->view_template) ?></td>
            <td><?= h($gallery->source) ?></td>
            <td class="actions">
                <?php
                $menu = new Backend\Lib\Menu\Menu();
                $menu->add(__('View'), ['action' => 'view', $gallery->id]);

                $dropdown = $menu->add('Dropdown');
                $dropdown->getChildren()->add(
                    __('Edit'),
                    ['action' => 'edit', $gallery->id],
                    ['icon' => 'edit']
                );
                $dropdown->getChildren()->add(
                    __('Add Item'),
                    ['action' => 'addItem', $gallery->id],
                    ['icon' => 'plus']
                );
                $dropdown->getChildren()->add(
                    __('Delete'),
                    ['action' => 'delete', $gallery->id],
                    ['icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $gallery->id)]
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
