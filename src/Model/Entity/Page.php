<?php
namespace Banana\Model\Entity;

use Cake\ORM\Entity;

/**
 * Page Entity.
 */
class Page extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'lft' => true,
        'rght' => true,
        'parent_id' => true,
        'title' => true,
        'slug' => true,
        'type' => true,
        'redirect_status' => true,
        'redirect_location' => true,
        'redirect_controller' => true,
        'redirect_page_id' => true,
        'layout_template' => true,
        'page_template' => true,
        'is_published' => true,
        'publish_start_date' => true,
        'publish_end_date' => true,
        'parent_page' => true,
        'child_pages' => true,
    ];

    protected function _getUrl()
    {
        return [
            'prefix' => false,
            'plugin' => 'Banana',
            'controller' => 'Pages',
            'action' => 'view',
            'pageid' => $this->id,
            'slug' => $this->slug
        ];
    }

    protected function _getPermaUrl() {
        return '/?pageid=' . $this->id;
    }

}
