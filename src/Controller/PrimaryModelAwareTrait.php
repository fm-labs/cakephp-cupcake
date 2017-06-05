<?php

namespace Banana\Controller;

use Cake\ORM\Table;

/**
 * Class PrimaryModelAwareTrait
 *
 * @package Banana\Controller
 */
trait PrimaryModelAwareTrait
{
    /**
     * @return null|Table
     */
    public function model()
    {
        $modelClass = $this->modelClass;

        list(, $alias) = pluginSplit($modelClass, true);

        if (isset($this->{$alias})) {
            return $this->{$alias};
        }

        return $this->loadModel();
    }
}