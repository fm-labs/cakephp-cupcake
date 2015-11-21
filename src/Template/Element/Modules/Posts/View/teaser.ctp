<?php
/**
 * @param View $this
 */

use Cake\View\View;

if (!$post) {
    echo '<div class="mod-error mod-500">Invalid post</div>';
    return;
}
?>
<div class="mod mod-post-teaser">
    <?php if ($post->teaser_html): ?>
        <div class="text-html">
            <?= $post->teaser_html; ?>
        </div>
    <?php endif; ?>

    <?php if ($post->teaser_link_caption): ?>
        <?= $this->Html->link($post->teaser_link_caption, $post->teaser_link_url); ?>
    <?php endif; ?>

</div>

