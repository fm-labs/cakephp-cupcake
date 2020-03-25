<?php
declare(strict_types=1);

namespace Banana\Exception;

use Cake\Core\Exception\Exception as CakeCoreException;

/**
 * Class ClassNotFoundException
 * @package Banana\Exception
 */
class NotSupportedException extends CakeCoreException
{
    /**
     * @var string
     */
    protected $_messageTemplate = 'Sry, %s is currently not supported';
}
