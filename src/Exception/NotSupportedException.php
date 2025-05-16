<?php
declare(strict_types=1);

namespace Cupcake\Exception;

use Cake\Core\Exception\CakeException;

/**
 * Class ClassNotFoundException
 *
 * @package Cupcake\Exception
 */
class NotSupportedException extends CakeException
{
    /**
     * @var string
     */
    protected string $_messageTemplate = 'Sry, %s is currently not supported';
}
