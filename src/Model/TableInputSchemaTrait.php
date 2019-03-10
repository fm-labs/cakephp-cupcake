<?php

namespace Banana\Model;

/**
 * Class TableInputSchemaTrait
 *
 * @package Banana\Model
 */
trait TableInputSchemaTrait
{
    /**
     * @param TableInputSchema $inputs
     * @return TableInputSchema
     */
    public function inputs(TableInputSchema $inputs = null)
    {
        if ($inputs === null) {
            if (!isset($this->inputs)) {
                $this->inputs = $this->_buildInputs(new TableInputSchema());
            }

            return $this->inputs;
        }

        return $this;
    }

    /**
     * @param TableInputSchema $inputs
     * @return TableInputSchema
     */
    protected function _buildInputs(TableInputSchema $inputs)
    {
        return $inputs;
    }
}
