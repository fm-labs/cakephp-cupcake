<?php
if (empty($params['gallery_id'])) {
    echo "GALLERY ID NOT SET";
    return;
}

try {
    $url = ['prefix' => false, 'plugin' => 'Banana',  'controller' => 'Galleries', 'action' => 'view', $params['gallery_id']];
    echo $this->requestAction('/banana/Galleries/view/' . $params['gallery_id']);
} catch (\Exception $ex) {
    debug($ex->getMessage());
}