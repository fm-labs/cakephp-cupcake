<?php $this->Html->addCrumb(__d('banana','Posts')); ?>
<?php $this->extend('/Admin/Content/index'); ?>
<?php
// EXTEND: TOOLBAR
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Post')), ['action' => 'add'], ['icon' => 'plus']);

// EXTEND: HEADING
$this->assign('heading', __d('banana','Posts'));
?>
<div class="posts index">

    <!-- Quick Search -->
    <div class="ui segment">
        <div class="ui form">
            <?= $this->Form->create(null, ['id' => 'quickfinder', 'action' => 'quick', 'class' => 'no-ajax']); ?>
            <?= $this->Form->input('post_id', [
                'options' => $postsList,
                'label' => false,
                'empty' => '- Quick Search -'
            ]); ?>
            <?= $this->Form->button('Go'); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>


    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Banana.Posts',
        'data' => $contents,
        'fields' => [
            'id',
            'created',
            'title' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'edit', $row->id]);
                }
            ],
            'refscope',
            'refid'
        ],
        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>
</div>
