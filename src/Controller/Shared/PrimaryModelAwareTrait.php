<?php

namespace Banana\Controller\Shared;


use Cake\Datasource\Exception\MissingModelException;

trait PrimaryModelAwareTrait
{

    public function model()
    {
        $modelClass = $this->modelClass;

        list(, $alias) = pluginSplit($modelClass, true);

        if (isset($this->{$alias})) {
            return $this->{$alias};
        }

        //throw new MissingModelException("The primary model has not been loaded");
        return $this->loadModel();
    }
}