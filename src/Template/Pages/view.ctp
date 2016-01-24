<?php
use Cake\Core\Configure;

$this->assign('title', $page->title);
?>
<?php $this->Html->meta('description', $metaDescription, ['block' => true]); ?>
<?php $this->Html->meta('keywords', $metaKeywords, ['block' => true]); ?>
<div class="page view <?= $page->cssclass ?>" id="<?= $page->cssid; ?>">
    <?php if (Configure::read('Banana.debug')): ?>
    <div class="debug">
        Page: <?= h($page->title); ?> [ID <?= h($page->id); ?>]<br />
        Type: <?= h($page->type); ?><br />
        Published: <?= h($page->is_published); ?><br />
    </div>
    <?php endif; ?>

    <h1 class="title"><?= h($page->title); ?></h1>

    <div class="posts">
        <?php foreach($page->published_posts as $post): ?>
        <?= $this->element('Banana.Posts/view', ['post' => $post]); ?>
        <?php endforeach; ?>
    </div>


    <div class="debug">
        <?php debug($page); ?>
    </div>
</div>
