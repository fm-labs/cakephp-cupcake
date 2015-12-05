<div class="post view <?= $post->cssclass; ?>" id="<?= $post->cssid ?>">
    <h1 class="title"><?= h($post->title); ?></h1>
    <div class="body">
        <?= $post->body_html; ?>
    </div>
</div>
