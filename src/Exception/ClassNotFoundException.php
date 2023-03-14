<?php
declare(strict_types=1);

namespace Cupcake\Exception;

use Cake\Core\Exception\CakeException;

/**
 * Class ClassNotFoundException
 *
 * @package Cupcake\Exception
 */
class ClassNotFoundException extends CakeException
{
    /**
     * @var string
     */
    protected $_messageTemplate = 'Class %s not found';
}
