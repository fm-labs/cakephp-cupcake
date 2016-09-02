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

    <div class="panel panel-primary">
        <div class="panel-heading">
            <i class="fa fa-page"></i>
            <strong><?= __('Page'); ?></strong>
            <?= h($content->title); ?>
        </div>
        <div class="panel-body">

            Public URL:
            <?= $this->Ui->link(
                $this->Html->Url->build($content->url, true),
                $content->url,
                ['target' => '_blank', 'icon' => 'external']
            ); ?>
            <div class="actions">

                <?= $this->Html->link(__('Edit {0}', __('Page')),
                    [ 'action' => 'edit', $content->id ],
                    [ 'class' => 'edit link-frame-modal btn btn-primary btn-sm', 'data-icon' => 'edit']);
                ?>
            </div>
        </div>
        <div class="panel-body">


            <?php $this->Tabs->start(); ?>
            <?php

            if ($content->type == 'content') {
                $this->Tabs->add(__d('banana', 'Posts'), [
                    'url' => ['action' => 'relatedPosts', $content->id]
                ]);
            }

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
            echo $this->Tabs->render();
            ?>
        </div>
    </div>


</div>
