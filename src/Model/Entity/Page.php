<?php
namespace Banana\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Behavior\Translate\TranslateTrait;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Page Entity.
 */
class Page extends Entity
{
    use TranslateTrait;

    private $__parentTheme;

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
        'theme' => true,
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

    protected function _getParentTheme()
    {

        if ($this->get('theme')) {
            return $this->get('theme');
        }

        if ($this->__parentTheme) {
            return $this->__parentTheme;
        }

        if ($this->get('parent_id')) {
            $Parent = TableRegistry::get('Banana.Pages');
            $parent = $Parent->get($this->get('parent_id'));
            return $this->__parentTheme = $parent->parent_theme;
        }

        return Configure::read('Banana.frontend.theme');
    }
}
