<?php
if (!isset($params['post_id'])) {
    debug('Post ID not set');
    return;
}
?>
<?php echo $this->requestAction('/banana/posts/view/' . $params['post_id']); ?>
