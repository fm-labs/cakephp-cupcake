<?php $this->Html->addCrumb(__d('banana','Galleries'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($gallery->title); ?>
<?= $this->Toolbar->addLink(
    __d('banana','Edit {0}', __d('banana','Gallery')),
    ['action' => 'edit', $gallery->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('banana','Delete {0}', __d('banana','Gallery')),
    ['action' => 'delete', $gallery->id],
    ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $gallery->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('banana','List {0}', __d('banana','Galleries')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('banana','New {0}', __d('banana','Gallery')),
    ['action' => 'add'],
    ['icon' => 'add']
) ?>
<?= $this->Toolbar->startGroup(__d('banana','More')); ?>
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
<?= $this->Toolbar->endGroup(); ?>
<div class="galleries view">
    <h2 class="ui header">
        <?= h($gallery->title) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __d('banana','Label'); ?></th>
            <th><?= __d('banana','Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __d('banana','Title') ?></td>
            <td><?= h($gallery->title) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','View Template') ?></td>
            <td><?= h($gallery->view_template) ?></td>
        </tr>
        <tr>
            <td><?= __d('banana','Source') ?></td>
            <td><?= h($gallery->source) ?></td>
        </tr>

        <tr>
            <td><?= __d('banana','Source Folder') ?></td>
            <td><?= h($gallery->source_folder) ?></td>
        </tr>



        <tr>
            <td><?= __d('banana','Id') ?></td>
            <td><?= $this->Number->format($gallery->id) ?></td>
        </tr>

        <tr class="text">
            <td><?= __d('banana','Desc Html') ?></td>
            <td><?= $this->Text->autoParagraph(h($gallery->desc_html)); ?></td>
        </tr>
    </table>
</div>
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __d('banana','Related {0}', __d('banana','Posts')) ?></h4>
    <?php if (!empty($gallery->posts)): ?>
    <table class="ui table">
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

                <?= $this->Form->postLink(__d('banana','Delete'), ['controller' => 'Posts', 'action' => 'delete', $posts->id], ['confirm' => __d('banana','Are you sure you want to delete # {0}?', $posts->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>


    <?= $this->Html->link(__d('banana','Add Item'), ['action' => 'addItem', $gallery->id]) ?>
    </div>

    <?php debug($gallery); ?>
</div>
