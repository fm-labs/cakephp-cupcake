<?php
declare(strict_types=1);

namespace Cupcake\Model;

/**
 * Interface EntityTypeHandlerInterface
 *
 * @package Cupcake\Model
 */
interface EntityTypeHandlerInterface
{
    /**
     * @return \Cupcake\Model\EntityTypeInterface
     */
    public function handler();
}
