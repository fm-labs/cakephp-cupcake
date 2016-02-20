<?php
namespace Banana\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Media\Lib\Media\MediaManager;

/**
 * Gallery Entity.
 *
 * @property int $id
 * @property string $title
 * @property string $desc_html
 */
class Gallery extends Entity
{

    protected $_parent;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    protected function _getParent()
    {
        if ($this->_parent === null && isset($this->_properties['parent_id'])) {
            $this->_parent = TableRegistry::get('Banana.Galleries')->get($this->_properties['parent_id']);
        }
        return $this->_parent;
    }

    protected function _getDescHtml()
    {
        if ($this->inherit_desc && $this->parent) {
            return $this->parent->desc_html;
        }

        return (isset($this->_properties['desc_html'])) ? $this->_properties['desc_html'] : null;
    }

    protected function _getImages()
    {
        switch ($this->_properties['source']) {
            case "folder":
                return $this->_loadImagesFromFolder();

            default:
                throw new \InvalidArgumentException("Gallery: Unknown source: " . $this->_properties['source']);
        }
    }

    protected function _getPublishedPosts()
    {
        return TableRegistry::get('Banana.Posts')
            ->find('published')
            ->find('media')
            ->where([
            'refscope' => 'Banana.Galleries',
            'refid' => $this->id,
        ])->all();
    }

    protected function _loadImagesFromFolder()
    {
        $folder = $this->_properties['source_folder'];

        $mm = MediaManager::get('default');
        $mm->open($folder);

        $files = $mm->listFiles();
        $images = [];

        array_walk($files, function($val) use (&$images, &$mm) {
            if (preg_match('/^_/', basename($val))) {
                return;
            }
            $images[] = $mm->getFileUrl($val);
        });

        return $images;
    }

    protected function _loadImagesFromPosts()
    {

    }
}
