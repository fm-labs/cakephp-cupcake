<?php

namespace Banana\View\Helper;

use Cake\View\Helper;

class ContentHelper extends Helper
{
    public $helpers = ['Html'];

    public function parseUrls($text) {

        preg_match_all('/\{\{(.*)\}\}/', $text, $matches);
        debug($matches);

        return $text;
    }
}