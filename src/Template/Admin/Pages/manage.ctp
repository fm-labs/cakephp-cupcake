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
$this->assign('title', $content->title);

// CONTENT
?>
<div class="pages">

    <h1>
        <?= h($content->title); ?>
        <?= $this->Html->link(__('Edit {0}', __('Page')),
            [ 'action' => 'edit', $content->id ],
            [ 'class' => 'edit link-frame btn btn-default btn-sm', 'data-icon' => 'edit']);
        ?>
    </h1>

    <!--
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->Ui->link(
                $this->Html->Url->build($content->url, true),
                $content->url,
                ['target' => '_blank', 'icon' => 'external']
            ); ?>
            <br />
            Slug: <?= h($content->slug); ?><br />
            Meta Title: <?= h($content->meta_title); ?><br />
            Meta Desc: <?= h($content->meta_desc); ?><br />
            Published: <?= $this->Ui->statusLabel($content->is_published); ?>
        </div>
    </div>
    -->

    <?php $this->Tabs->start(); ?>



    <?php
    switch($content->type):
        case 'content':
        case 'blog_category':
        case 'static':
            $this->Tabs->add(__d('banana', 'Posts'), [
                'url' => ['action' => 'relatedPosts', $content->id]
            ]);

            $this->Tabs->add(__d('banana', 'Meta'), [
                'url' => ['action' => 'relatedPageMeta', $content->id]
            ]);

            //$this->Tabs->add(__d('banana', 'Sitemap'), [
            //    'url' => ['action' => 'relatedPageMeta', $content->id]
            //]);

            $this->Tabs->add(__d('banana', 'Page Details'), [
                'url' => ['action' => 'view', $content->id]
            ]);

            $this->Tabs->add(__d('banana', 'Content Modules'), [
                'url' => ['action' => 'relatedContentModules', $content->id]
            ]);

            break;

        case 'shop_category':
            $this->Tabs->add(__d('banana', 'Shop Category'), [
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'view', $content->redirect_location]
            ]);

            $this->Tabs->add(__d('banana', 'Meta'), [
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'relatedPageMeta', $content->redirect_location]
            ]);

            $this->Tabs->add(__d('banana', 'Content Modules'), [
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'relatedContentModules', $content->redirect_location]
            ]);
            break;


    endswitch;
    echo $this->Tabs->render();
    ?>
</div>
