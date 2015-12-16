<?php $this->Html->addCrumb(__d('banana','Galleries'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('banana','Edit {0}', __d('banana','Gallery'))); ?>
<?= $this->Toolbar->addPostLink(
    __d('banana','Delete'),
    ['action' => 'delete', $gallery->id],
    ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $gallery->id)]
)
?>
<?= $this->Toolbar->addLink(
    __d('banana','List {0}', __d('banana','Galleries')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
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
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('banana','Edit {0}', __d('banana','Gallery')) ?>
    </h2>
    <?= $this->Form->create($gallery); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
            echo $this->Form->input('parent_id', ['empty' => true]);
            echo $this->Form->input('title');
            echo $this->Form->input('inherit_desc');
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
        <?= $this->Form->button(__d('banana','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>

<div class="ui divider"></div>

<div class="related">
<div class="ui basic segment">
    <h4 class="ui header"><?= __d('banana','Related {0}', __d('banana','Posts')) ?></h4>
    <?php if (!empty($gallery->posts)): ?>
        <table class="ui table">
            <thead>
            <tr>
                <th><?= __d('banana','Id') ?></th>
                <th><?= __d('banana','Title') ?></th>
                <th><?= __d('banana','Image File') ?></th>
                <th><?= __d('banana','Template') ?></th>
                <th><?= __d('banana','Cssclass') ?></th>
                <th><?= __d('banana','Cssid') ?></th>
                <th><?= __d('banana','Published') ?></th>
                <th class="actions"><?= __d('banana','Actions') ?></th>
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
                        <?= $this->Html->link(__d('banana','View'), ['controller' => 'Posts', 'action' => 'view', $posts->id]) ?>
                        <?= $this->Html->link(__d('banana','Edit'), ['controller' => 'Posts', 'action' => 'edit', $posts->id]) ?>
                        <?= $this->Html->link(__d('banana','Copy'), ['controller' => 'Posts', 'action' => 'copy', $posts->id]) ?>
                        <?= $this->Form->postLink(__d('banana','Delete'), ['controller' => 'Posts', 'action' => 'delete', $posts->id],
                            ['confirm' => __d('banana','Are you sure you want to delete # {0}?', $posts->id)]) ?>

                    </td>
                </tr>

            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?= $this->Ui->link(__d('banana','Add Gallery Item'),
        ['action' => 'addItem', $gallery->id],
        ['class' => 'ui tiny button', 'icon' => 'add']
    ) ?>
</div>

<?php debug($gallery); ?>