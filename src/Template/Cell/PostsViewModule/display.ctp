<?php
if (!isset($params['post_id'])) {
    debug('Post ID not set');
    return;
}
?>
<?php
if ($params['show_teaser']):
    echo $this->requestAction('/banana/posts/teaser/' . $params['post_id']);
else:
    echo $this->requestAction('/banana/posts/view/' . $params['post_id']);
endif
?>