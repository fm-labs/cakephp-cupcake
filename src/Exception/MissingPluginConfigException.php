<?php

namespace Banana\Exception;

use Cake\Core\Exception\MissingPluginException;

/**
 * Class MissingPluginConfigException
 * @package Banana\Exception
 */
class MissingPluginConfigException extends MissingPluginException
{
    /**
     * @var string
     */
    protected $_messageTemplate = 'Plugin config for %s could not be found.';
}
