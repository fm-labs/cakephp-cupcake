<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 11/20/15
 * Time: 5:46 PM
 */

namespace Banana\Model\Entity\Module;


use Cake\Filesystem\Folder;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Media\Lib\Media\MediaManager;

class FlexsliderModule extends BaseModule
{
    protected $_defaultParams = [
        'source' => 'folder',
        'media_config' => 'gallery',
        'media_folder' => '',
        'media_files' => '', // attachment list
    ];

    protected function _getViewPath()
    {
        return 'Banana.Modules/Flexslider';
    }

    protected function _getFormData()
    {
        return [
            'module' => $this,
            'sources' => [
                'folder' => 'From Folder',
                'media_folder' => 'Media Gallery Folder',
                'media_selection' => 'Media Gallery Selection'
            ]
        ];
    }

    protected function _getViewData()
    {

        $images = $this->_getImagesFromSource();

        return [
            'module' => $this,
            'params' => $this->params_arr,
            'images' => $images
        ];
    }

    protected function _getImagesFromSource()
    {
        $images = [];
        switch ($this->source) {
            case "folder":
                if (!$this->media_config) {
                    throw new \InvalidArgumentException('Flexslider: Source folder path not specified');
                }
                $mm = MediaManager::create($this->media_config);
                $mm->open($this->media_folder);

                $images = $mm->listFileUrls();
                break;

        }
        return $images;
    }

    public function processForm(Entity $entity, array $formData)
    {
        // TODO: Implement processForm() method.
    }
}