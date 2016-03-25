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

    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Banana.Galleries',
        'data' => $galleries,
        'fields' => [
            'id',
            'parent' => [
                'formatter' => function($val) {
                    if ($val) {
                        return $this->Html->link($val->title, ['action' => 'edit', $val->id]);
                    }
                }
            ],
            'title' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'edit', $row->id]);
                }
            ],
            'view_template',
            'source'
        ],
        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>
</div>
