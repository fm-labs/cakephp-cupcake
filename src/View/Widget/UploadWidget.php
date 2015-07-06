<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 2/6/15
 * Time: 7:25 PM
 */

namespace Banana\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\WidgetInterface;

class UploadWidget implements WidgetInterface
{

    /**
     * StringTemplate instance.
     *
     * @var \Cake\View\StringTemplate
     */
    protected $_templates;

    /**
     * Constructor.
     *
     * @param \Cake\View\StringTemplate $templates Templates list.
     */
    public function __construct($templates, $Html = null, $Form = null)
    {
        $this->_templates = $templates;
        debug(get_class($Form));
    }

    /**
     * Converts the $data into one or many HTML elements.
     *
     * @param array $data The data to render.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string Generated HTML for the widget element.
     */
    public function render(array $data, ContextInterface $context)
    {
        debug($data);
        $html = "";
        if (isset($data['file']) && $file = $data['file']) {
            //$html .= h($file->source) . '<br />';
            //$html .= $this->Html->image($file->url, ['width' => 200]);
        }
        //$this->Form->input('image_upload', ['type' => 'file']);
    }

    /**
     * {@inheritDoc}
     */
    public function secureFields(array $data)
    {
        if (!isset($data['name']) || $data['name'] === '') {
            return [];
        }
        return [$data['name']];
    }
}
