<?php
declare(strict_types=1);

namespace Banana\Controller;

/**
 * Class PrimaryModelAwareTrait
 *
 * @package Banana\Controller
 */
trait PrimaryModelAwareTrait
{
    /**
     * @return null|\Cake\ORM\Table
     */
    public function model()
    {
        $modelClass = $this->modelClass;

        [, $alias] = pluginSplit($modelClass, true);

        if (isset($this->{$alias})) {
            return $this->{$alias};
        }

        return $this->loadModel();
    }
}
