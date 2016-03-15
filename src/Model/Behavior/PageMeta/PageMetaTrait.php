<?php

namespace Banana\Model\Behavior\PageMeta;

use Cake\ORM\TableRegistry;

trait PageMetaTrait
{
    //protected $_pageMetaModel;

    protected function _getMeta()
    {
        if (!array_key_exists('meta', $this->_properties)) {

            $this->_properties['meta'] = TableRegistry::get('Banana.PageMetas')
                ->find()
                ->where(['PageMetas.model' => $this->_pageMetaModel, 'PageMetas.foreignKey' => $this->id])
                ->first();
        }

        return $this->_properties['meta'];
    }

    protected function _getMetaTitle()
    {
        $meta = $this->_getMeta();
        if ($meta) {
            return $meta['title'];
        }
    }

    protected function _getMetaDesc()
    {
        $meta = $this->_getMeta();
        if ($meta) {
            return $meta['description'];
        }
    }

    protected function _getMetaKeywords()
    {
        $meta = $this->_getMeta();
        if ($meta) {
            return $meta['keywords'];
        }
    }

    protected function _getMetaRobots()
    {
        $meta = $this->_getMeta();
        if ($meta) {
            return $meta['robots'];
        }
    }

    protected function _getMetaLang()
    {
        $meta = $this->_getMeta();
        if ($meta) {
            return $meta['lang'];
        }
    }
}