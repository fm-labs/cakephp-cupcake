<?php
declare(strict_types=1);

namespace Cupcake\Exception;

use Cake\Core\Exception\Exception as CakeCoreException;

/**
 * Class ClassNotFoundException
 * @package Cupcake\Exception
 */
class ClassNotFoundException extends CakeCoreException
{
    /**
     * @var string
     */
    protected $_messageTemplate = 'Class %s not found';
}
