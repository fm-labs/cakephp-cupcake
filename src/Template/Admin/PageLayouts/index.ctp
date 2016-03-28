<?php $this->Html->addCrumb(__d('banana','Page Layouts')); ?>

<?php $this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Page Layout')), ['action' => 'add'], ['icon' => 'add']); ?>
<div class="pageLayouts index">


    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Banana.PageLayouts',
        'data' => $pageLayouts,
        'fields' => [
            'id',
            'name' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'edit', $row->id]);
                }
            ],
            'template',
            'is_default'
        ],
        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>
</div>
