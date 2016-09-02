<?php $this->Html->addCrumb(__d('banana','Pages')); ?>
<?php $this->extend('/Admin/Content/index'); ?>
<?php
// TOOLBAR
$this->Toolbar->addLink(__d('banana','{0} (Tree)', __d('banana','Pages')), ['action' => 'index'], ['icon' => 'sitemap']);
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Page')), ['action' => 'add'], ['icon' => 'file-o', 'class' => 'link-frame-modal']);
$this->Toolbar->addLink(__d('banana','Repair Tree'), ['action' => 'repair'], ['icon' => 'wrench']);

// HEADING
$this->assign('heading', __d('banana','Pages'));

// CONTENT
?>
<div class="pages index">

    <!-- Quick Search -->
    <div class="panel panel-default">
        <div class="panel-heading">
            Quick Search
        </div>
        <div class="panel-body">
            <?= $this->Form->create(null, ['id' => 'quickfinder', 'action' => 'quick', 'class' => 'no-ajax']); ?>
            <?= $this->Form->input('page_id', [
                'options' => $pagesTree,
                'label' => false,
                'empty' => '- Quick Search -'
            ]); ?>
            <?= $this->Form->button('Go'); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
    <div class="actions right grouped">
        <ul>
            <!--
            <li><?= $this->Html->link(
                    __('Reorder (tree)'),
                    [
                        'controller' => 'Sort', 'action' => 'reorder', 'model' => 'Banana.Pages',
                        'field' => 'lft',  'order' => 'asc',
                        'scope' => []
                    ],
                    ['class' => 'link-frame btn btn-default']); ?></li>
            -->
        </ul>
    </div>
    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        //'sortable' => true,
        //'sortUrl' => ['plugin' => 'Banana', 'controller' => 'Sort', 'action' => 'tableSort'],
        'model' => 'Banana.Pages',
        'data' => $contents,
        'fields' => [
            'id',
            'title' => [
                'formatter' => function($val, $row) use ($pagesTree) {
                    return $this->Html->link($pagesTree[$row->id], ['action' => 'edit', $row->id]);
                }
            ],
            'type',
            'is_published' => [
                'formatter' => function($val, $row) {
                    return $this->Ui->statusLabel($val);
                }
            ]
        ],
        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Preview'), ['action' => 'preview', ':id'], ['class' => 'edit']],
            [__d('shop','Copy'), ['action' => 'copy', ':id'], ['class' => 'copy']],
            [__d('shop','Move Up'), ['action' => 'moveUp', ':id'], ['class' => 'move-up']],
            [__d('shop','Move Down'), ['action' => 'moveDown', ':id'], ['class' => 'move-down']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>

</div>