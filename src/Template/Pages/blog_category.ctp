<div class="page view <?= $page->cssclass ?>" id="<?= $page->cssid; ?>">
    <h1 class="title"><?= h($page->title); ?></h1>

    <div class="posts">
        <?php foreach($posts as $post): ?>
            <?= $this->element('Banana.Posts/view', ['post' => $post]); ?>
        <?php endforeach; ?>
    </div>
</div>