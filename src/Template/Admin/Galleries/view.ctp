<?php $this->Html->addCrumb(__('Galleries'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($gallery->title); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Gallery')),
    ['action' => 'edit', $gallery->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('Gallery')),
    ['action' => 'delete', $gallery->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $gallery->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Galleries')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Gallery')),
    ['action' => 'add'],
    ['icon' => 'add']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
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
<?= $this->Toolbar->endGroup(); ?>
<div class="galleries view">
    <h2 class="ui header">
        <?= h($gallery->title) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __('Label'); ?></th>
            <th><?= __('Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __('Title') ?></td>
            <td><?= h($gallery->title) ?></td>
        </tr>
        <tr>
            <td><?= __('View Template') ?></td>
            <td><?= h($gallery->view_template) ?></td>
        </tr>
        <tr>
            <td><?= __('Source') ?></td>
            <td><?= h($gallery->source) ?></td>
        </tr>

        <tr>
            <td><?= __('Source Folder') ?></td>
            <td><?= h($gallery->source_folder) ?></td>
        </tr>



        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($gallery->id) ?></td>
        </tr>

        <tr class="text">
            <td><?= __('Desc Html') ?></td>
            <td><?= $this->Text->autoParagraph(h($gallery->desc_html)); ?></td>
        </tr>
    </table>
</div>
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __('Related {0}', __('Posts')) ?></h4>
    <?php if (!empty($gallery->posts)): ?>
    <table class="ui table">
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Title') ?></th>
            <th><?= __('Image File') ?></th>
            <th><?= __('Template') ?></th>
            <th><?= __('Cssclass') ?></th>
            <th><?= __('Cssid') ?></th>
            <th><?= __('Published') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($gallery->posts as $posts): ?>
        <tr>
            <td><?= h($posts->id) ?></td>
            <td><?= h($posts->title) ?></td>
            <td><?= h($posts->image_file) ?></td>
            <td><?= h($posts->template) ?></td>
            <td><?= h($posts->cssclass) ?></td>
            <td><?= h($posts->cssid) ?></td>
            <td><?= h($posts->is_published) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'Posts', 'action' => 'view', $posts->id]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'Posts', 'action' => 'edit', $posts->id]) ?>
                <?= $this->Html->link(__('Copy'), ['controller' => 'Posts', 'action' => 'copy', $posts->id]) ?>

                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Posts', 'action' => 'delete', $posts->id], ['confirm' => __('Are you sure you want to delete # {0}?', $posts->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>


    <?= $this->Html->link(__('Add Item'), ['action' => 'addItem', $gallery->id]) ?>
    </div>

    <?php debug($gallery); ?>
</div>
