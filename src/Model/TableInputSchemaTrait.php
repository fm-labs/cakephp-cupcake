<?php
declare(strict_types=1);

namespace Banana\Model;

/**
 * Class TableInputSchemaTrait
 *
 * @package Banana\Model
 */
trait TableInputSchemaTrait
{
    /**
     * @param \Banana\Model\TableInputSchema $inputs
     * @return \Banana\Model\TableInputSchema
     */
    public function inputs(?TableInputSchema $inputs = null)
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
     * @param \Banana\Model\TableInputSchema $inputs
     * @return \Banana\Model\TableInputSchema
     */
    protected function _buildInputs(TableInputSchema $inputs)
    {
        return $inputs;
    }
}
