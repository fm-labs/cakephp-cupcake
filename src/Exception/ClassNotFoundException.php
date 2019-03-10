<?php

namespace Banana\Exception;

use Cake\Core\Exception\Exception as CakeCoreException;

/**
 * Class ClassNotFoundException
 * @package Banana\Exception
 */
class ClassNotFoundException extends CakeCoreException
{
    /**
     * @var string
     */
    protected $_messageTemplate = 'Class %s not found';
}
