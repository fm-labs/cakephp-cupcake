<?php

namespace Banana\View\Helper;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\View\Helper;

use Cake\View\Helper\HtmlHelper;
use Cake\View\View;

class ContentHelper extends HtmlHelper
{

    protected $_urlPlaceholderCache = [];

    /**
     * Replace url placeholders with format `{{Plugin.Model:id}}`
     *
     * @param $text
     * @return mixed
     */
    public function parseUrlPlaceholders($text) {

        //@todo Implement modelMap feature
        $modelMap = [
            'Content.Pages' => 'Banana.Pages',
            'Content.Posts' => 'Banana.Posts'
        ];

        $text = preg_replace_callback('/\{\{(.*)\}\}/U', function($matches) use ($modelMap) {

            $placeholder = $matches[1];

            if (isset($this->_urlPlaceholderCache[$placeholder])) {
                return $this->_urlPlaceholderCache[$placeholder];
            }

            $args = explode(':', $placeholder);
            $modelName = array_shift($args);

            if (count($args) < 1) {
                $id = null;
            } else {
                $id = array_shift($args);
            }

            if (isset($modelMap[$modelName])) {
                $modelName = $modelMap[$modelName];
            }

            try {
                $Table = TableRegistry::get($modelName);
                $entity = $Table->find()->where(['id' => $id])->contain([])->first();

                $url = ($entity) ? $entity->url : null;

            } catch (\Exception $ex) {
                $url = null;
                debug($ex->getMessage());
            }

            $url = ($url) ?: '/';
            $url = Router::url($url);

            return $this->_urlPlaceholderCache[$placeholder] = $url;

        }, $text);

        return $text;
    }

    public function userHtml($text)
    {
        $text = $this->parseUrlPlaceholders($text);

        return $text;
    }


    public function getCrumbList(array $options = [], $startText = false) {

        $this->templater()->add([
            'breadcrumb_list' => '<ol{{attrs}}>{{items}}</ol>',
            'breadcrumb_item' => '<li{{attrs}}>{{content}}</li>'
        ]);

        $defaults = ['separator' => '', 'escape' => true];
        $options += $defaults;

        $separator = $options['separator'];
        $escape = $options['escape'];
        unset($options['separator'], $options['escape']);


        $crumbs = $this->_prepareCrumbs($startText, $escape);
        if (empty($crumbs)) {
            return null;
        }

        $result = '';
        $listOptions = $options;
        foreach ($crumbs as $which => $crumb) {
            $options = [
                'itemscope' => true,
                'itemtype' => "http://schema.org/breadcrumb"
            ];
            if (empty($crumb[1])) {
                $elementContent = $crumb[0];
            } else {
                $linkAttrs = $crumb[2];
                $linkAttrs += ['itemprop' => 'url'];
                $elementContent = $this->link($crumb[0], $crumb[1], $linkAttrs);
            }

            $result .= $this->formatTemplate('breadcrumb_item', [
                'content' => $elementContent,
                'attrs' => $this->templater()->formatAttributes($options)
            ]);
        }


        return $this->formatTemplate('breadcrumb_list', [
            'items' => $result,
            'attrs' => $this->templater()->formatAttributes($listOptions)
        ]);
    }
}