<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/17/16
 * Time: 10:48 PM
 */

namespace Banana\Model\Entity\Page;


use Cake\Datasource\EntityInterface;

interface PageInterface
{
    //function getPageType(EntityInterface $entity);

    function getPageType();
    function getPageTitle();
    function getPageUrl();
    function getPageAdminUrl();
    function getPageChildren();
}