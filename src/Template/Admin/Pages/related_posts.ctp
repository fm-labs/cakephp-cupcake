<?php $this->loadHelper('Banana.Content'); ?>
<div class="related posts">

    <?php if (count($posts) < 1): ?>
    No posts found
    <?php endif; ?>


    <?= $this->cell('Backend.DataTable', [[
        'paginate' => false,
        'model' => 'Banana.Posts',
        'data' => $posts,
        'fields' => [
            'id',
            'is_published' => [
                'title' => __('Published'),
                'formatter' => function($val, $row) {
                    return $this->Ui->statusLabel($val);
                }
            ],
            'title' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['controller' => 'Posts', 'action' => 'edit', $row->id], ['class' => 'link-frame']);
                }
            ]
        ],
        'rowActions' => [
            [__d('shop','Edit'), ['controller' => 'Posts', 'action' => 'edit', ':id'], ['class' => 'edit link-frame']],
            [__d('shop','Delete'), ['controller' => 'Posts', 'action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>

    <div class="actions">
        <?= $this->Html->link(
            __('Add {0}', __('Post')),
            ['controller' => 'Posts', 'action' => 'add', 'refscope' => 'Banana.Pages',  'refid' => $content->id],
            ['class' => 'link-frame btn btn-default']); ?>
    </div>

    <?php debug($posts); ?>
</div>