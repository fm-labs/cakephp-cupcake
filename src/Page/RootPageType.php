<?php
namespace Banana\Page;

use Cake\Datasource\EntityInterface;

class RootPageType extends AbstractPageType
{
    public function getUrl(EntityInterface $page)
    {
        return '/';
    }
}