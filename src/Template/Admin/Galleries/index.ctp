<?php $this->Html->addCrumb(__d('banana','Galleries')); ?>

<?php $this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Gallery')), ['action' => 'add'], ['icon' => 'add']); ?>
<?= $this->Toolbar->addLink(
    __d('banana','List {0}', __d('banana','Posts')),
    ['controller' => 'Posts', 'action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('banana','New {0}', __d('banana','Post')),
    ['controller' => 'Posts', 'action' => 'add'],
    ['icon' => 'add']
) ?>
<div class="galleries index">
    <table class="ui table compact striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('view_template') ?></th>
            <th><?= $this->Paginator->sort('source') ?></th>
            <th class="actions"><?= __d('banana','Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($galleries as $gallery): ?>
        <tr>
            <td><?= $this->Number->format($gallery->id) ?></td>
            <td><?= $this->Html->link($gallery->title, ['action' => 'edit', $gallery->id]) ?></td>
            <td><?= h($gallery->view_template) ?></td>
            <td><?= h($gallery->source) ?>
                <?php if ($gallery->source == 'folder') {
                    echo '<br /><small>' . h($gallery->source_folder) . '</small>';
                }
                ?>
            </td>
            <td class="actions">
                <?php
                $menu = new Backend\Lib\Menu\Menu();
                $menu->add(
                    __d('banana','Edit'),
                    ['action' => 'edit', $gallery->id],
                    ['icon' => 'edit']
                );

                $dropdown = $menu->add('Dropdown');
                $dropdown->getChildren()->add(
                    __d('banana','Add Item'),
                    ['action' => 'addItem', $gallery->id],
                    ['icon' => 'plus']
                );
                $dropdown->getChildren()->add(
                    __d('banana','Delete'),
                    ['action' => 'delete', $gallery->id],
                    ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $gallery->id)]
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
