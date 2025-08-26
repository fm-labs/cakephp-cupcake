<?php
declare(strict_types=1);

namespace Cupcake\Exception;

use Cake\Core\Exception\CakeException;

/**
 * Class ClassNotFoundException
 *
 * @package Cupcake\Exception
 */
class SecurityException extends CakeException
{
    /**
     * @var string
     */
    protected string $_messageTemplate = 'Security error: %s';

    /**
     * @var int
     */
    protected int $_defaultCode = 400;
}
