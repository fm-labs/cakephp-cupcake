<?php
namespace Banana\Model\Entity;

use Cake\ORM\Entity;

/**
 * Post Entity.
 */
class Post extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true, //@todo Define accessible fields
        'title' => true,
        'slug' => true,
        'subheading' => true,
        'teaser_html' => true,
        'body_html' => true,
        'image_file' => true,
        'image_file_upload' => true,
        'is_published' => true,
        'publish_start_datetime' => true,
        'publish_end_datetime' => true,
    ];

    /**
     * @deprecated Use _getViewUrl() instead
     */
    protected function _getUrl()
    {
        return $this->_getViewUrl();
    }

    protected function _getPermaUrl() {
        return '/?postid=' . $this->id;
    }

    protected function _getViewUrl()
    {
        return ['prefix' => false, 'plugin' => 'Banana', 'controller' => 'Posts', 'action' => 'view',  $this->id];
    }

    protected function _getTeaserLinkUrl()
    {
        #if (isset($this->_properties['teaser_link_href'])) {
        #    return $this->_properties['teaser_link_href'];
        #}
        return $this->_getViewUrl();
    }


    protected function _getRealTeaserLinkCaption()
    {
        if (!empty($this->_properties['teaser_link_caption'])) {
            return $this->_properties['teaser_link_caption'];
        }
        return __('Continue');
    }

    protected function _getRealTeaserLinkHref()
    {
        if (!empty($this->_properties['teaser_link_href'])) {
            return $this->_properties['teaser_link_href'];
        }
        return $this->viewUrl;
    }
}
