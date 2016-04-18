<?php
$this->loadHelper('Backend.Tabs');
//$this->extend('/Admin/Content/edit');

// EXTEND: TOOLBAR
$this->Toolbar->addLink(
    __d('banana','Delete'),
    ['action' => 'delete', $content->id],
    ['icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to delete # {0}?', $content->id)]
);
$this->Toolbar->addLink(__d('banana','Edit {0}', __d('banana','Page')), ['action' => 'edit', $content->id], ['icon' => 'edit']);
$this->Toolbar->addLink(__d('banana','Preview'), ['action' => 'preview', $content->id], ['icon' => 'eye', 'target' => '_preview']);
$this->Toolbar->addLink(__d('banana','List {0}', __d('banana','Pages')), ['action' => 'index'], ['icon' => 'list']);
$this->Toolbar->addLink(__d('banana','New {0}', __d('banana','Page')), ['action' => 'add'], ['icon' => 'plus']);


// HEADING
$this->assign('title', sprintf('[%s] %s (#%s)', 'Pages', $content->title, $content->id));

// CONTENT
?>
<style>
    /*
    .related-posts .card {
        max-height: 300px;
        overflow-y: scroll;
    }
    */
</style>
<div class="pages">

    <?php $this->Tabs->start(); ?>
    <?php $this->Tabs->add(__d('banana','Page')); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?= $this->Html->link($content->title, ['action' => 'edit', $content->id], ['class' => 'header']); ?>
        </div>
        <div class="panel-body"><?= $this->Ui->link(
                $this->Html->Url->build($content->url, true),
                $content->url,
                ['target' => '_blank', 'icon' => 'external']
            ); ?>
            <br />
            Meta Title: <?= h($content->meta_title); ?><br />
            Meta Desc: <?= h($content->meta_desc); ?><br />
            Type: <?= h($content->type); ?><br />
            Published: <?= $this->Ui->statusLabel($content->is_published); ?><br />
        </div>
    </div>

    <?= $this->cell('Backend.EntityView', [ $content ], [
        'title' => false,
        'model' => 'Banana.Pages',
        'fields' => [
            'title' => [
                'formatter' => function($val, $entity) {
                    return $this->Html->link($val, ['action' => 'edit', $entity->id], ['class' => 'link-frame']);
                }
            ],
            'parent_id' => [
                'title' => __('Parent Page'),
                'formatter' => function($val, $entity) {
                    if (!$entity->parent_id) {
                        return __('Root Page');
                    }

                    $title = ($entity->parent_page) ? $entity->parent_page->title : $entity->parent_id;
                    return $this->Html->link($title, ['action' => 'view', $entity->id], ['class' => 'link-frame']);
                }
            ],
            'is_published' => ['formatter' => 'boolean'],
            'url' => ['formatter' => 'link']
        ],
        'exclude' => ['id', 'level', 'lft', 'rght', 'meta', 'meta_lang', 'meta_title', 'meta_desc', 'meta_keywords', 'meta_robots', 'parent_page']
    ]); ?>

    <?php $this->Tabs->add(__d('banana','Related Posts')); ?>

    <h3>Related Posts</h3>

    <div class="related-posts">
        <?= $this->cell('Backend.DataTable', [[
            'paginate' => false,
            'model' => 'Banana.Posts',
            'data' => $content->posts,
            'fields' => [
                'id',
                'created',
                'title' => [
                    'formatter' => function($val, $row) {
                        return $this->Html->link($val, ['action' => 'edit', $row->id], ['class' => 'link-frame']);
                    }
                ]
            ],
            'rowActions' => [
                [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            ]
        ]]);
        ?>
    </div>


    <!-- RELATED CONTENT MODULES -->
    <?php $this->Tabs->add('Related Content Modules'); ?>
        <h3>Related content modules</h3>
        <?= $this->element('Banana.Admin/Content/related_content_modules', compact('content', 'sections')); ?>
        <br />
        <?= $this->Ui->link('Build a new module for this page', [
            'controller' => 'ModuleBuilder',
            'action' => 'build2',
            'refscope' => 'Banana.Pages',
            'refid' => $content->id
        ], ['class' => 'ui button', 'icon' => 'plus']); ?>

    <?php // $this->Tabs->add('Link existing module'); ?>
    <!--
        <h3>Link existing module</h3>
        <div class="ui form">
            <?= $this->Form->create(null, ['url' => ['action' => 'linkModule', $content->id]]); ?>
            <?= $this->Form->input('refscope', ['default' => 'Banana.Pages']); ?>
            <?= $this->Form->input('refid', ['default' => $content->id]); ?>
            <?= $this->Form->input('module_id', ['options' => $availableModules]); ?>
            <?= $this->Form->input('section'); ?>
            <?= $this->Form->submit('Link module'); ?>
            <?= $this->Form->end(); ?>
        </div>
    -->

    <?php echo $this->Tabs->render(); ?>

</div>
