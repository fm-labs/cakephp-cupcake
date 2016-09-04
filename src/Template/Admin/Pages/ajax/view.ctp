<?php
$this->Html->addCrumb(__('Pages'), ['action' => 'index']);
$this->Html->addCrumb(__d('banana','Edit {0}', $content->title));

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
<div class="pages view">

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
        'exclude' => ['id', 'level', 'lft', 'rght', 'meta', 'meta_lang', 'meta_title', 'meta_desc', 'meta_keywords', 'meta_robots', 'parent_page', 'content_modules', 'posts']
    ]); ?>
</div>
