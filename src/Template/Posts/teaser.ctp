<article class="post teaser">
    <div class="image">
        <?php if ($post->teaser_image): ?>
        <?= $this->Html->image($post->teaser_image->url); ?>
        <?php endif; ?>
    </div>
    <h1 class="title">
        <?= h($post->title); ?>
    </h1>
    <div class="text">
        <?= $this->Content->userHtml($post->teaser_html); ?>
    </div>
    <div class="action">
        <?= $this->Html->link($post->teaser_link_title, $post->teaser_link_url); ?>
    </div>
</article>
