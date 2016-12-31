<?php

namespace Banana\Exception;

use Cake\Core\Exception\MissingPluginException;

class MissingPluginManifestException extends MissingPluginException
{
    protected $_messageTemplate = 'Plugin manifest for %s could not be found.';
}