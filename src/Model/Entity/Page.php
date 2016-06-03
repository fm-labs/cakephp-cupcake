<?php
namespace Banana\Model\Entity;

use Banana\Core\Banana;
use Banana\Model\Behavior\PageMeta\PageMetaTrait;
use Banana\Model\Entity\Page\PageInterface;
use Banana\Page\AbstractPageType;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\ORM\Behavior\Translate\TranslateTrait;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Utility\Hash;

/**
 * Page Entity.
 */
class Page extends Entity implements PageInterface
{
    use TranslateTrait;
    use PageMetaTrait;

    private $__parentTheme;

    /**
     * @var string PageMetaTrait model definition
     */
    protected $_pageMetaModel = 'Banana.Pages';

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true, //@TODO define accessible fields
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

    protected $_virtual = [
        'url',
        'meta_title',
        'meta_desc',
        'meta_keywords',
        'meta_robots',
        'meta_lang'
    ];

    /**
     * @var AbstractPageType
     */
    protected $_handler;

    function getPageTitle()
    {
        return $this->title;
    }


    function getPageType()
    {
        return $this->type;
    }

    /**
     * @return AbstractPageType|null
     * @throws \Exception
     */
    public function getPageHandler()
    {
        if ($this->_handler === null) {
            $this->_handler = Banana::getPagehandler($this);
            if (!$this->_handler) {
                throw new \Exception(sprintf('Page Handler not found for type %s', $this->type));
            }
        }
        return $this->_handler;
    }


    function getPageUrl()
    {
        return $this->getPageHandler()->getUrl();
    }

    function getPageAdminUrl()
    {
        return $this->getPageHandler()->getAdminUrl();
    }

    public function getPageChildren()
    {
        return $this->getPageHandler()->getChildren();
    }

    public function isPagePublished()
    {
        return $this->getPageHandler()->isPublished();
    }

    public function isPageHiddenInNav()
    {
        return $this->getPageHandler()->isHiddenInNav();
    }

    public function getPath()
    {
        return TableRegistry::get('Banana.Pages')
            ->find('path', ['for' => $this->id]);
    }

    /**
     * @return array|string
     * @deprecated Use getPageUrl() instead
     */
    protected function _getUrl()
    {
        return $this->getPageUrl();
    }

    protected function _getPermaUrl() {
        return '/?page_id=' . $this->id;
    }

    /**
     * @return mixed
     * @todo Replace with ParentTrait
     */
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

        return Configure::read('Banana.Frontend.theme');
    }

    /**
     * @return \Cake\Datasource\ResultSetInterface
     * @todo Cache results
     */
    protected function _getPublishedPosts()
    {
        return TableRegistry::get('Banana.Posts')
            ->find('published')
            ->where(['Posts.refscope' => 'Banana.Pages', 'Posts.refid' => $this->id])
            ->order(['Posts.pos' => 'ASC', 'Posts.id' => 'ASC'])
            ->all();
    }




    /** PAGE AWARE **/




}
