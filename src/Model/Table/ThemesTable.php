<?php

namespace Banana\Model\Table;

use Banana\Model\ArrayTable;
use Content\Lib\ContentManager;

/**
 * Class ThemesTable
 * @package Banana\Model\Table
 */
class ThemesTable extends ArrayTable
{

    /**
     * Return array table data
     *
     * @return array
     */
    public function getItems()
    {
        return ContentManager::getThemesAvailable()->toArray();
    }
}
