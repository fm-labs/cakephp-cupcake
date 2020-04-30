<?php
declare(strict_types=1);

namespace Cupcake\View\Helper;

use Cupcake\Lib\Status;
use Cake\View\Helper;

/**
 * Class StatusHelper
 *
 * @package Cupcake\View\Helper
 */
class StatusHelper extends Helper
{
    /**
     * Render status html
     *
     * @param $status
     * @return string
     * @todo Make use of UiHelper::label() or LabelHelper::status() from Bootstrap plugin
     */
    public function label($status)
    {
        if (is_object($status) && $status instanceof Status) {
            return $status->toHtml();
        }

        return $status;
    }
}
