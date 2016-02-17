<?php

namespace Banana\View\Helper;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\View\Helper;

class ContentHelper extends Helper
{
    public $helpers = ['Html'];

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
}