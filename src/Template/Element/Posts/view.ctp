<?php
try {
    if ($post->teaser_html):
        echo $this->requestAction('/banana/posts/teaser/' . $post->id);
    else:
        echo $this->requestAction('/banana/posts/view/' . $post->id);
    endif;
} catch (\Exception $ex) {
    debug($ex->getMessage());
    \Cake\Log\Log::error('Failed to fetch post with ID ' . $post->id . ': ' . $ex->getMessage());
}