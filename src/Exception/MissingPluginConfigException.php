<?php

namespace Banana\Exception;

use Cake\Core\Exception\Exception;

/**
 * Class MissingPluginConfigException
 * @package Banana\Exception
 */
class MissingPluginConfigException extends Exception
{
    /**
     * @var string
     */
    protected $_messageTemplate = 'Plugin config for %s could not be found.';
}
