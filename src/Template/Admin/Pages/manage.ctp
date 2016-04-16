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

    <?php $this->Tabs->add(__d('banana', 'Page')); ?>

    <div class="panel panel-default">
        <div class="panel-heading"><?= $content->title; ?></div>
        <div class="panel-body">
            <?= $this->Ui->link(
                $this->Html->Url->build($content->url, true),
                $content->url,
                ['target' => '_blank', 'icon' => 'external']
            ); ?>
            <br />
            <small>Slug: <?= h($content->slug); ?></small><br />
            <small>Meta Title: <?= h($content->meta_title); ?></small><br />
            <small>Meta Desc: <?= h($content->meta_desc); ?></small><br />
            <small>Published: <?= $this->Ui->statusLabel($content->is_published); ?></small><br />
        </div>
    </div>

    <?php $this->Tabs->add(__d('banana', 'Edit Page'), [
        'url' => ['action' => 'edit', $content->id]
    ]); ?>


    <?php if (in_array($content->type, ['content', 'static'])): ?>
        <?php $this->Tabs->add(__d('banana', 'Related Posts'), [
            'url' => ['action' => 'relatedPosts', $content->id]
        ]); ?>

        <?php $this->Tabs->add(__d('banana', 'Related Meta'), [
            'url' => ['action' => 'relatedPageMeta', $content->id]
        ]); ?>

        <?php $this->Tabs->add(__d('banana', 'Related Content Modules'), [
            'url' => ['action' => 'relatedContentModules', $content->id]
        ]); ?>
    <?php endif; ?>

    <?php if (in_array($content->type, ['shop_category'])): ?>
        <?php $this->Tabs->add(__d('banana', 'Related Shop Category'), [
            'url' => ['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'view', $content->redirect_location]
        ]); ?>

        <?php $this->Tabs->add(__d('banana', 'Related Meta'), [
            'url' => ['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'relatedPageMeta', $content->redirect_location]
        ]); ?>

        <?php $this->Tabs->add(__d('banana', 'Related Content Modules'), [
            'url' => ['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'relatedContentModules', $content->redirect_location]
        ]); ?>
    <?php endif; ?>

    <?php echo $this->Tabs->render(); ?>

</div>
