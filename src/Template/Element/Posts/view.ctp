<?php
if (!$post->is_published) {
    return;
}

try {
    $action = 'view';
    if ($post->teaser_html) {
        $action = 'teaser';
    }
    $url = $this->Url->build(['plugin' => 'banana', 'controller' => 'Posts', 'action' => $action, $post->id]);
    echo $this->requestAction($url);
} catch (\Exception $ex) {
    debug($ex->getMessage());
    \Cake\Log\Log::error('Failed to fetch post with ID ' . $post->id . ': ' . $ex->getMessage());
}