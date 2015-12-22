<?php
if (empty($params['gallery_id'])) {
    echo "GALLERY ID NOT SET";
    return;
}

try {
    $url = ['prefix' => false, 'plugin' => 'Banana',  'controller' => 'Galleries', 'action' => 'view', $params['gallery_id']];
    $url = $this->Url->build($url);
    echo $this->requestAction($url);
} catch (\Exception $ex) {
    debug($ex->getMessage());
}