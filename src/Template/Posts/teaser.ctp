<div class="posts view teaser">
    <div class="image">
        <?php if ($post->image_file): ?>
        <?= $this->Html->image($post->image_file->url); ?>
        <?php endif; ?>
    </div>
    <h2 class="heading">
        <?= h($post->title); ?>
    </h2>
    <div class="text">
        <?= $post->teaser_html; ?>
    </div>
    <div class="action">
        <?= $this->Html->link($post->real_teaser_link_caption, $post->real_teaser_link_href); ?>
    </div>
</div>
