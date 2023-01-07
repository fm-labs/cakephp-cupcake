<?php
declare(strict_types=1);

namespace Cupcake\View\Helper;

use Cake\View\Helper;

/**
 * Class StatusHelper
 *
 * @package Cupcake\View\Helper
 */
class StatusHelper extends Helper
{
    public function initialize(array $config): void
    {
        if (\Cake\Core\Plugin::isLoaded('Sugar')) {
            \Sugar\View\Helper\FormatterHelper::register('status', function ($val, $extra, $params, $view) {
                return $this->label($val);
            });
        }
    }

    /**
     * Render status html.
     *
     * @param $status
     * @return string
     */
    public function label($status)
    {
        if (is_object($status) && $status instanceof \Cupcake\Lib\Status) {
            //return $status->toHtml();
            if (\Cake\Core\Plugin::isLoaded('Bootstrap')) {
                $BadgeHelper = $this->_View->loadHelper('Bootstrap.Badge');
                return $BadgeHelper->create($status->getLabel(), ['class' => $status->getClass()]);
            }
            return $status->getLabel();
        }
        return sprintf('<span class="status">%s</span>', $status);
    }
}
