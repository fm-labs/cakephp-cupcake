<?php
namespace Banana\Model\Entity;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
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
        'page_layout_id' => true,
        'page_template' => true,
        'is_published' => true,
        'publish_start_date' => true,
        'publish_end_date' => true,
        'parent_page' => true,
        'child_pages' => true,
    ];

    protected function _getUrl()
    {
        $defaultUrl = [
            'prefix' => false,
            'plugin' => 'Banana',
            'controller' => 'Pages',
            'action' => 'view',
            'pageid' => $this->id,
            'slug' => $this->slug
        ];

        switch ($this->type) {
            case "controller":
                $url = $this->_getRedirectControllerUrl();
                break;
            case "cell":
            case "module":
            default:
                $url = $defaultUrl;
        }

        return $url;
    }

    protected function _getRedirectControllerUrl()
    {
        $controller = explode('::', $this->redirect_controller);
        $action = 'index';
        $params = [];
        if (count($controller) == 2) {
            list($controller, $action) = $controller;

            $action = explode(':', $action);
            if (count($action) == 2) {
                list($action, $args) = $action;

                $args = explode(',', $args);
                array_walk($args, function ($val, $idx) use (&$params) {
                    $val = trim($val);
                    if (preg_match('/^[\{](.*)[\}]$/', $val, $matches)) {
                        $val = $this->get($matches[1]);
                        $params[$matches[1]] = $val;
                    } else {
                        $params[] = $val;
                    }
                });

                //debug($params);
            } elseif (count($action) == 1) {
                $action = $action[0];
            } else {
                throw new Exception("Malformed controller params");
            }

        } elseif (count($controller) == 1) {
            $controller = $controller[0];
        } else {
            throw new Exception("Malformed controller location");
        }

        list($plugin, $controller) = pluginSplit($controller);
        $url = ['prefix' => false, 'plugin' => $plugin, 'controller' => $controller, 'action' => $action, 'page_id' => $this->id];
        $url = array_merge($params, $url);
        return $url;
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
