<?php
declare(strict_types=1);

namespace Banana\Model;

/**
 * Interface EntityTypeHandlerInterface
 *
 * @package Banana\Model
 */
interface EntityTypeHandlerInterface
{
    /**
     * @return \Banana\Model\EntityTypeInterface
     */
    public function handler();
}
