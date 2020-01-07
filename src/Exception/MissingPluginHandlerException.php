<?php

namespace Banana\Exception;

use Cake\Core\Exception\Exception;

/**
 * Class MissingPluginHandlerException
 * @package Banana\Exception
 */
class MissingPluginHandlerException extends Exception
{
    /**
     * @var string
     */
    protected $_messageTemplate = 'Plugin handler class %s for plugin %s could not be found.';
}
