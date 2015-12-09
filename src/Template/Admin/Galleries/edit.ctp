<?php $this->Html->addCrumb(__('Galleries'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Gallery'))); ?>
<?= $this->Toolbar->addPostLink(
    __('Delete'),
    ['action' => 'delete', $gallery->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $gallery->id)]
)
?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Galleries')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
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
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Edit {0}', __('Gallery')) ?>
    </h2>
    <?= $this->Form->create($gallery); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
            echo $this->Form->input('title');
            echo $this->Form->input('desc_html', [
                'type' => 'htmleditor',
                'editor' => [
                    'image_list_url' => ['controller' => 'Data', 'action' => 'editorImageList'],
                    'link_list_url' => ['controller' => 'Data', 'action' => 'editorLinkList'],
                ]
            ]);
            echo $this->Form->input('view_template');
            echo $this->Form->input('source');
            echo $this->Form->input('source_folder');
        ?>
        </div>
    </div>
    <div class="ui basic segment">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>

<div class="ui divider"></div>

<div class="related">
<div class="ui basic segment">
    <h4 class="ui header"><?= __('Related {0}', __('Posts')) ?></h4>
    <?php if (!empty($gallery->posts)): ?>
        <table class="ui table">
            <thead>
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
            </thead>
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
                        <?= $this->Form->postLink(__('Delete'), ['controller' => 'Posts', 'action' => 'delete', $posts->id],
                            ['confirm' => __('Are you sure you want to delete # {0}?', $posts->id)]) ?>

                    </td>
                </tr>

            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?= $this->Ui->link(__('Add Gallery Item'),
        ['action' => 'addItem', $gallery->id],
        ['class' => 'ui tiny button', 'icon' => 'add']
    ) ?>
</div>

<?php debug($gallery); ?>