<?php
declare(strict_types=1);

namespace Cupcake\Health;

/**
 * Interface HealthInterface
 *
 * @package Cupcake\Health
 */
interface HealthCheckInterface
{
    /**
     * @return \Cupcake\Health\HealthStatus
     */
    public function getHealthStatus(): HealthStatus;
}
