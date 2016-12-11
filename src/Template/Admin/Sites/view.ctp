<?php $this->Breadcrumbs->add(__('Sites'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($site->title); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Site')),
    ['action' => 'edit', $site->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('Site')),
    ['action' => 'delete', $site->id],
    ['data-icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $site->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Sites')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Site')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Parent Sites')),
    ['controller' => 'Sites', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Parent Site')),
    ['controller' => 'Sites', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="sites view">
    <h2 class="ui header">
        <?= h($site->title) ?>
    </h2>

    <?php

    echo $this->cell('Backend.EntityView', [ $site ], [
        'title' => $site->title,
        'model' => 'Banana.Sites',
    ]);

    ?>

<!--
    <table class="ui attached celled striped table">


        <tr>
            <td><?= __('Parent Site') ?></td>
            <td><?= $site->has('parent_site') ? $this->Html->link($site->parent_site->title, ['controller' => 'Sites', 'action' => 'view', $site->parent_site->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __('Alias') ?></td>
            <td><?= h($site->alias) ?></td>
        </tr>
        <tr>
            <td><?= __('Title') ?></td>
            <td><?= h($site->title) ?></td>
        </tr>
        <tr>
            <td><?= __('Hostname') ?></td>
            <td><?= h($site->hostname) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($site->id) ?></td>
        </tr>

    </table>
</div>
-->
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __('Related {0}', __('Sites')) ?></h4>
    <?php if (!empty($site->child_sites)): ?>
    <table class="ui table">
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Parent Id') ?></th>
            <th><?= __('Alias') ?></th>
            <th><?= __('Title') ?></th>
            <th><?= __('Hostname') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($site->child_sites as $childSites): ?>
        <tr>
            <td><?= h($childSites->id) ?></td>
            <td><?= h($childSites->parent_id) ?></td>
            <td><?= h($childSites->alias) ?></td>
            <td><?= h($childSites->title) ?></td>
            <td><?= h($childSites->hostname) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'Sites', 'action' => 'view', $childSites->id]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'Sites', 'action' => 'edit', $childSites->id]) ?>

                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Sites', 'action' => 'delete', $childSites->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childSites->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>



