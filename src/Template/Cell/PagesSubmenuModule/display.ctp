<?php
echo $this->element(
    $params['element_path'], [
        'class' => $params['class'],
        'menu' => $params['menu'],
        'level' => $params['level'],
        'element' => $params['element_path']
    ]
); ?>
