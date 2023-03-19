<?php

namespace Cupcake\Health\Check;

use Cupcake\Health\HealthCheckGeneratorInterface;
use Cupcake\Health\HealthStatus;

class PhpVersionCheck implements HealthCheckGeneratorInterface
{

    /**
     * @inheritDoc
     */
    public function getHealthStatus(): \Generator
    {
        if (version_compare(PHP_VERSION, '7.4.0') < 0) {
            yield HealthStatus::crit('Your PHP version must be equal or higher than 7.4.0 to use CakePHP.');
        } else {
            yield HealthStatus::ok(__d('admin', 'You are using supported PHP version {0}', PHP_VERSION));
        }
    }
}
