<?php
declare(strict_types=1);

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
     * @param \Banana\Model\TableInputSchema|null $inputs
     * @return mixed
     */
    public function inputs(?TableInputSchema $inputs = null);
}
