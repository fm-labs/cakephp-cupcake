<?php
if (!isset($post)) {
    debug('Post not set');
    return;
}
?>
<div class="post">
    <div class="datetime">
        <?= h($post->created); ?>
    </div>
    <div>Perma URL: <?= h($post->perma_url) ?></div>
    <h1><?= $this->Html->link($post->title, $post->url) ?></h1>

    <div class="body">
        <?= $post->body_html; ?>
    </div>

    <div class="debug">
        <?php debug($post); ?>
    </div>

</div>