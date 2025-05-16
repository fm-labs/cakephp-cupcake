<?php
declare(strict_types=1);

namespace Cupcake\Health\Check;

use Cake\Datasource\ConnectionManager;
use Cupcake\Health\HealthCheckGeneratorInterface;
use Cupcake\Health\HealthStatus;
use Exception;
use Generator;

class DatabaseConnectionCheck implements HealthCheckGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function getHealthStatus(): Generator
    {
        foreach (ConnectionManager::configured() as $name) {
            try {
                $connection = ConnectionManager::get($name);
                $connection->getDriver()->connect();
                yield HealthStatus::ok(sprintf("Datasource '%s' connection is OK", $name));
            } catch (Exception $ex) {
                yield HealthStatus::crit(sprintf("Datasource '%s' connection FAILED: %s", $name, $ex->getMessage()));
            }
        }
    }
}
