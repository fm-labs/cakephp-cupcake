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
<div class="mod mod-post">
    <?php $rendered = $this->requestAction('/content/posts/view/' . $post->id); ?>
    <?php echo $rendered ?>
</div>

