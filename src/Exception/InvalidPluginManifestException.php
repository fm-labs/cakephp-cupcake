<?php

namespace Banana\Exception;

use Cake\Core\Exception\MissingPluginException;

class InvalidPluginManifestException extends MissingPluginException
{
    protected $_messageTemplate = 'Plugin manifest for %s is malformed.';
}