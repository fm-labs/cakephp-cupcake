<?php
declare(strict_types=1);

namespace Cupcake\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;
use Cupcake\Lib\Status;

/**
 * Class StatusHelper
 *
 * @package Cupcake\View\Helper
 */
class StatusHelper extends Helper
{
    use StringTemplateTrait;

    protected array $_defaultConfig = [
        'templates' => [
            'status_display' => '<span class="{{class}}">{{status}}</span>',
            'status_boolean_true' => '<i class="fa fa-check {{class}}"{{attrs}} />',
            'status_boolean_false' => '<i class="fa fa-times {{class}}"{{attrs}} />',
        ],
    ];

    public function initialize(array $config): void
    {
        if (\Cake\Core\Plugin::isLoaded('Admin')) {
            \Admin\View\Helper\FormatterHelper::register('status', function ($val, $extra, $params, $view) {
                return $this->label($val);
            });
        }
    }

    /**
     * Render status as HTML.
     *
     * @param $status
     * @return string
     */
    public function label($status, array $options = []): string
    {
        return $this->display($status, $options);
    }

    /**
     * Render status as HTML.
     *
     * @param $status
     * @return string
     */
    public function display($status, array $options = []): string
    {
        if (is_object($status) && $status instanceof Status) {
            //return $status->toHtml();
            if (\Cake\Core\Plugin::isLoaded('Bootstrap')) {
                $BadgeHelper = $this->_View->loadHelper('Bootstrap.Badge');

                return $BadgeHelper->create($status->getLabel(), ['class' => $status->getClass()]);
            }

            return $status->getLabel();
        }

        $out = $this->templater()->format('status_display', [
            'class' => $options['class'] ?? '',
            'status' => $status,
            'attrs' => $this->templater()->formatAttributes($options, ['class', 'label']),
        ]);

        return $out;
    }

    /**
     * @param bool $status Status value
     * @param array $options Additional options
     * @param array $map Status map
     * @return string|null
     */
    public function boolean(bool $status, array $options = [], array $map = []): ?string
    {
        $options += ['label' => null, 'class' => null];
        $label = $class = null;
        extract($options, EXTR_IF_EXISTS);

        if (empty($map)) {
            $map = [
                0 => [__d('cupcake', 'No'), 'text-danger'],
                1 => [__d('cupcake', 'Yes'), 'text-success'],
            ];
        }

        if (!is_bool($status)) {
            $status = !!$status;
        }

        if (!$class) {
            $class = 'default';
        }
        if (!$label) {
            $label = $status === true ? __d('cupcake', 'Yaps') : __d('cupcake', 'Nope');
        }

        if (array_key_exists((int)$status, $map)) {
            $mapped = $map[$status];
            if (is_string($mapped)) {
                $label = $mapped;
            } elseif (is_array($mapped) && count($mapped) == 2) {
                [$label, $class] = $mapped;
            }
        }

        $options['class'] = $class;
        $options['title'] = $label;
        unset($options['label']);

        $template = $status === true ? 'status_boolean_true' : 'status_boolean_false';

        return $this->templater()->format($template, [
            'class' => $options['class'] ?? '',
            //'status' => $status,
            'attrs' => $this->templater()->formatAttributes($options, ['class', 'label']),
        ]);
    }
}
