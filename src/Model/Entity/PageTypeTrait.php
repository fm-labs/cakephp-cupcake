<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/17/16
 * Time: 11:47 PM
 */

namespace Banana\Model\Entity;


use Banana\Core\Banana;
use Banana\Page\AbstractPageType;

trait PageTypeTrait
{

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
            $this->_handler = Banana::getPagehandler($this->getPageType());
            if (!$this->_handler) {
                throw new \Exception(sprintf('Page Handler not found for type %s', $this->type));
            }
        }
        return $this->_handler;
    }


    function getPageUrl()
    {
        return $this->getPageHandler()->getUrl($this);
    }



    function getPageAdminUrl()
    {
        return $this->getPageHandler()->getAdminUrl($this);
    }


    public function getPageChildren()
    {
        return $this->getPageHandler()->getChildren($this);
    }
}