<?php
if (!$post->is_published) {
    return;
}

try {
    $action = 'view';
    //@TODO only rely on use_teaser flag
    if ($post->teaser_html || $post->use_teaser) {
        $action = 'teaser';
    }
    $url = $this->Url->build(['plugin' => 'Banana', 'controller' => 'Posts', 'action' => $action, $post->id]);
    echo $this->requestAction($url);
} catch (\Exception $ex) {
    debug($ex->getMessage());
    \Cake\Log\Log::error('Failed to fetch post with ID ' . $post->id . ': ' . $ex->getMessage());
}