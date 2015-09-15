<?php
namespace Banana\Model\Entity;

use Cake\ORM\Entity;

/**
 * PageLayout Entity.
 */
class PageLayout extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     * Note that '*' is set to true, which allows all unspecified fields to be
     * mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    protected function _getTheme()
    {
        list($theme,) = pluginSplit($this->template);
        return $theme;
    }

    protected function _getLayout()
    {
        list(,$layout) = pluginSplit($this->template);
        return $layout;
    }

    protected function _getSectionsList()
    {
        $sections = array_walk(explode(',', $this->sections), 'trim');
        return $sections;
    }
}
