<?php

namespace Banana\Exception;

use Cake\Core\Exception\MissingPluginException;

class MissingPluginHandlerException extends MissingPluginException
{
    protected $_messageTemplate = 'Plugin handler for %s could not be found.';
}