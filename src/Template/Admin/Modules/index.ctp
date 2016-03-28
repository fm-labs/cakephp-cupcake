<?php $this->Html->addCrumb(__d('banana','Modules')); ?>
<div class="modules index">


    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Banana.Modules',
        'data' => $modules,
        'fields' => [
            'id',
            'name' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'view', $row->id]);
                }
            ],
            'title',
            'path'
        ],
        'rowActions' => [
            [__d('shop','View'), ['action' => 'view', ':id'], ['class' => 'view']],
            [__d('shop','Edit'), ['controller' => 'ModuleBuilder', 'action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>
</div>
