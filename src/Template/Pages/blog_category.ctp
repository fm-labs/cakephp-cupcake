<?php foreach ($posts as $post): ?>
<article class="blog-post">
    <h1><?= $post->title; ?></h1>

    <?= $this->Content->userHtml($post->body_html); ?>
</article>
<?php endforeach; ?>