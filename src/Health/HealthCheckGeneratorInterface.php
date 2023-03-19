<?php
declare(strict_types=1);

namespace Cupcake\Health;

/**
 * Interface HealthInterface
 *
 * @package Cupcake\Health
 */
interface HealthCheckGeneratorInterface
{
    /**
     * @return \Generator
     */
    public function getHealthStatus(): \Generator;
}
