<?php $this->loadHelper('Backend.Tabs'); ?>
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
<?= $this->Tabs->start(); ?>
<?= $this->Tabs->add($gallery->title); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('banana','Edit {0}', __d('banana','Gallery')) ?>
    </h2>
    <?= $this->Form->create($gallery); ?>
    <div class="ui form">
    <?php
        echo $this->Form->input('parent_id', ['empty' => true]);
        echo $this->Form->input('title');
        echo $this->Form->input('inherit_desc');
        echo $this->Form->input('desc_html', [
            'type' => 'htmleditor',
            'editor' => [
                'image_list_url' => '@Banana.HtmlEditor.default.imageList',
                'link_list_url' => '@Banana.HtmlEditor.default.linkList'
            ]
        ]);
        echo $this->Form->input('view_template');
        echo $this->Form->input('source', ['empty' => true]);
        echo $this->Form->input('source_folder', ['empty' => true]);
    ?>
    </div>
    <?= $this->Form->button(__d('banana','Submit')) ?>
    <?= $this->Form->end() ?>

</div>

<?php
$this->Tabs->add(__d('banana', 'Posts'), [
    //'url' => ['action' => 'relatedPosts', $content->id]
]);
?>

    <div class="related">
    <?= $this->cell('Backend.DataTable', [[
        'paginate' => false,
        'model' => 'Banana.Posts',
        'data' => $galleryPosts,
        'sortable' => true,
        'sortUrl' => ['plugin' => 'Banana', 'controller' => 'Sort', 'action' => 'tableSort'],
        'fields' => [
            'id',
            'pos',
            'is_published' => [
                'title' => __('Published'),
                'formatter' => function($val, $row) {
                    return $this->Ui->statusLabel($val);
                }
            ],
            'created',
            'image_file' => [
                'formatter' => function($val) {
                    if (!$val) {
                        return "";
                    }
                    return $this->Html->image($val->url, ['width' => 50]);
                }
            ],
            'title' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'edit', $row->id]);
                }
            ],
        ],
        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>
        <div class="actions">
            <?= $this->Ui->link(__d('banana','Add Gallery Item'),
                ['action' => 'addItem', $gallery->id],
                ['class' => 'btn btn-default', 'icon' => 'plus']
            ) ?>

            <?= $this->Html->link(
                __('Reorder (asc)'),
                [
                    'controller' => 'Sort', 'action' => 'reorder', 'model' => 'Banana.Posts',
                    'field' => 'pos',  'order' => 'asc',
                    'scope' => ['refscope' => 'Banana.Galleries', 'refid' => $gallery->id]
                ],
                ['class' => 'link-frame btn btn-default']); ?>
            <?= $this->Html->link(
                __('Reorder (desc)'),
                [
                    'controller' => 'Sort', 'action' => 'reorder', 'model' => 'Banana.Posts',
                    'field' => 'pos',  'order' => 'desc',
                    'scope' => ['refscope' => 'Banana.Galleries', 'refid' => $gallery->id]
                ],
                ['class' => 'link-frame btn btn-default']); ?>
        </div>


    </div>

<?= $this->Tabs->render(); ?>

<?php debug($gallery); ?>