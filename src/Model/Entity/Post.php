<?php
namespace Banana\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

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
        //'image_file_upload' => true,
        'image_files' => true,
        //'image_files_upload' => true,
        'priority' => true,
        'is_published' => true,
        'publish_start_datetime' => true,
        'publish_end_datetime' => true,
    ];

    protected function _setTitle($val)
    {
        return trim($val);
    }

    /**
     * Get Teaser Link Url.
     * Fallback to view url, if body is set.
     *
     * @return array|null
     */
    protected function _getTeaserLinkUrl()
    {
        // customer teaser link
        if (!empty($this->_properties['teaser_link_href'])) {
            return $this->_properties['teaser_link_href'];
        }

        if (!empty($this->_properties['body_html'])) {
            return $this->_getViewUrl();
        }

        return null;
    }

    /**
     * @return mixed
     */
    protected function _getTeaserLinkTitle()
    {
        if (!empty($this->_properties['teaser_link_caption'])) {
            return $this->_properties['teaser_link_caption'];
        }
        return __d('banana','Read more');
    }

    /**
     * Virtual Field 'url'
     * @deprecated Use _getViewUrl() instead
     */
    protected function _getUrl()
    {
        return $this->_getViewUrl();
    }

    /**
     * Virtual Field 'perma_url'
     * @return string
     */
    protected function _getPermaUrl() {
        return '/?postid=' . $this->id;
    }

    /**
     * Virtual Field 'view_url'
     * @return array
     */
    protected function _getViewUrl()
    {
        return [
            'prefix' => false, 'plugin' => 'Banana', 'controller' => 'Posts',
            'action' => 'view',  $this->id
        ];
    }

    /**
     * Virtual field 'image'. Wrapper for 'image_file'
     * @return mixed
     */
    protected function _getImage()
    {
        return $this->image_file;
    }

    /**
     * Virtual field 'images'. Wrapper for 'image_files'
     * @return mixed
     */
    protected function _getImages()
    {
        return $this->image_files;
    }

    /**
     * Virtual field 'teaser_image'. Wrapper for 'teaser_image_file'
     * Fallback to 'image_file'
     * @return mixed
     */
    protected function _getTeaserImage()
    {
        if (!empty($this->_properties['teaser_image_file'])) {
            return $this->_properties['teaser_image_file'];
        }
        return $this->image_file;
    }

    protected function _getReftitle()
    {
        if (!$this->refscope) {
            return null;
        }

        $ref = pluginSplit($this->refscope);

        //$refmodel = TableRegistry::get($this->refscope);
        //$ref = $refmodel->get($this->refid);

        return __d('banana',"{0} with ID {1}", Inflector::singularize($ref[1]), $this->refid);
    }

    protected function _getRefurl()
    {
        if (!$this->refscope) {
            return;
        }

        $ref = pluginSplit($this->refscope);

        return ['plugin' => $ref[0], 'controller' => $ref[1], 'action' => 'view', $this->refid];
    }



    /*
    protected function _getImageFiles()
    {
        if (is_string($this->_properties['image_files'])) {
            $this->_properties['image_files'] = explode(',', $this->_properties['image_files']);
        }

        return $this->_properties['image_files'];
    }

    protected function _setImageFiles($val)
    {
        if (is_array($val)) {
            $val = join(',', $val);
        }

        return $val;
    }
    */
}
