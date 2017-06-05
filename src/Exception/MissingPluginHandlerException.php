<?php

namespace Banana\Exception;

use Cake\Core\Exception\MissingPluginException;

/**
 * Class MissingPluginHandlerException
 * @package Banana\Exception
 */
class MissingPluginHandlerException extends MissingPluginException
{
    /**
     * @var string
     */
    protected $_messageTemplate = 'Plugin handler for %s could not be found.';
}
