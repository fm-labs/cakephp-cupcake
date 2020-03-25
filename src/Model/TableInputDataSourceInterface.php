<?php
declare(strict_types=1);

namespace Banana\Model;

interface TableInputDataSourceInterface
{
    /**
     * Returns a list of data options
     *
     * @return array
     */
    public function getInputList($fieldName);
}
