<div class="post view <?= $post->cssclass; ?>" id="<?= $post->cssid ?>">
    <h1 class="title">
        <?= h($post->title); ?>
    </h1>
    <div class="image">
        <?php if ($post->image): ?>
            <?= $this->Html->image($post->image->url); ?>
        <?php endif; ?>
    </div>
    <div class="body">
        <?= $post->body_html; ?>
    </div>
</div>
