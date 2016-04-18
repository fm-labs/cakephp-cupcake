<?php $this->Html->addCrumb(__d('banana','Posts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($post->title); ?>
<?= $this->Toolbar->addLink(
    __d('banana','Edit {0}', __d('banana','Post')),
    ['action' => 'edit', $post->id],
    ['class' => 'item', 'icon' => 'edit']
) ?>
<?= $this->Toolbar->addPostLink(
    __d('banana','Delete {0}', __d('banana','Post')),
    ['action' => 'delete', $post->id],
    ['class' => 'item', 'icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $post->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('banana','List {0}', __d('banana','Posts')),
    ['action' => 'index'],
    ['class' => 'item', 'icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('banana','New {0}', __d('banana','Post')),
    ['action' => 'add'],
    ['class' => 'item', 'icon' => 'plus']
) ?>

<div class="posts view">

    <?= $this->cell('Backend.EntityView', [ $post ], [
        'title' => $post->title,
        'model' => 'Banana.Posts',
        'fields' => [
            'title' => [
                'formatter' => function($val, $entity) {
                    return $this->Html->link($val, ['action' => 'edit', $entity->id]);
                }
            ],
            'is_published' => ['formatter' => 'boolean'],
            'url' => ['formatter' => 'link']
        ],
        'exclude' => ['meta', 'content_modules']
    ]); ?>

</div>
