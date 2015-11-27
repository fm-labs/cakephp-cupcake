<?php
$path = $this->get('src');
$attr = [
   'alt' => $this->get('alt'),
   'title' => $this->get('title')
];
echo $this->Html->image($path, $attr);
