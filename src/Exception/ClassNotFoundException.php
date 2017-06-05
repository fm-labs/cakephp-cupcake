<?php

namespace Banana\Exception;

/**
 * Class ClassNotFoundException
 * @package Banana\Exception
 */
class ClassNotFoundException extends \Cake\Core\Exception\Exception
{
    /**
     * @var string
     */
    protected $_messageTemplate = 'Class %s not found';
}
