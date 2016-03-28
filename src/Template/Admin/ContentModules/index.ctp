<?php $this->Html->addCrumb(__d('banana','Content Modules')); ?>
<div class="contentModules index">


    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Banana.ContentModules',
        'data' => $contentModules,
        'fields' => [
            'id',
            'refscope',
            'refid',
            'module_id' => [
                'formatter' => function($val, $row) {
                    return $row->has('module')
                        ? $this->Html->link($row->module->name, ['controller' => 'ModuleBuilder', 'action' => 'edit', $row->module->id])
                        : '';
                }
            ],
            'title' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'edit', $row->id]);
                }
            ],
            'section',
            'template'
        ],
        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>
</div>
