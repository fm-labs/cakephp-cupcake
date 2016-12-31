<?php

namespace Banana\Exception;

use Cake\Core\Exception\MissingPluginException;

class MissingPluginConfigException extends MissingPluginException
{
    protected $_messageTemplate = 'Plugin config for %s could not be found.';
}