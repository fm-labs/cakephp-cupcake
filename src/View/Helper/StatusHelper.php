<?php

namespace Banana\View\Helper;


use Banana\Lib\Status;
use Cake\View\Helper;

class StatusHelper extends Helper
{
    public function label($status)
    {
        if (is_object($status) && $status instanceof Status) {
            return $status->toHtml();
        }

        return $status;
    }
}