<?php

namespace Banana\Model;

/**
 * Interface TableInputSchemaInterface
 *
 * @package Banana\Model
 */
interface TableInputSchemaInterface
{
    /**
     * Getter / Setter for input schema
     *
     * @param TableInputSchema|null $inputs
     * @return mixed
     */
    public function inputs(TableInputSchema $inputs = null);
}
