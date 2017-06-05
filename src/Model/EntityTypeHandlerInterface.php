<?php

namespace Banana\Model;

/**
 * Interface EntityTypeHandlerInterface
 *
 * @package Banana\Model
 */
interface EntityTypeHandlerInterface
{
    /**
     * @return EntityTypeInterface
     */
    public function handler();
}
