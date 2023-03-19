<?php

namespace Cupcake\Health\Check;

use Cake\Datasource\ConnectionManager;
use Cupcake\Health\HealthCheckGeneratorInterface;
use Cupcake\Health\HealthStatus;

class DatabaseConnectionCheck implements HealthCheckGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function getHealthStatus(): \Generator
    {
        foreach (ConnectionManager::configured() as $name) {
            //$config = ConnectionManager::getConfig($name);
            $ok = false;
            try {
                $connection = ConnectionManager::get($name);
                $connection->connect();
                $ok = true;
            } catch (\Exception $ex) {
                yield HealthStatus::crit($ex->getMessage());
            } finally {
                yield HealthStatus::ok(sprintf("Connection '%s' OK", $name));
            }
        }
    }
}