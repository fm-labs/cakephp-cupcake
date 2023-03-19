<?php

namespace Cupcake\Health\Check;

use Cake\Datasource\ConnectionManager;
use Cupcake\Health\HealthCheckGeneratorInterface;
use Cupcake\Health\HealthStatus;


/**
 * Class Health
 *
 * ! Experimental !
 *
 * @package Admin\System
 */
class CakePhpHealthCheck implements HealthCheckGeneratorInterface
{
    /**
     * @return array
     */
    public static function check(): array
    {
        $checks = [];
        $checks['core_permissions_write'] = function () {
            $dirs = [LOGS, CACHE, TMP];
            foreach ($dirs as $dir) {
                yield [sprintf("Directory writeable: %s", $dir), is_writable($dir)];
            }
        };
        $checks['core_permissions_read'] = function () {
            $dirs = [CONFIG, WWW_ROOT];
            foreach ($dirs as $dir) {
                yield [sprintf("Directory readable: %s", $dir), is_readable($dir)];
            }
        };
        $checks['db_connections'] = function () {
        };

        $results = [];
        foreach ($checks as $check => $callable) {
            foreach (call_user_func($callable) as $result) {
                $results[$check][] = $result;
            }
        }

        return $results;
    }

    /**
     * @inheritDoc
     */
    public function getHealthStatus(): \Generator
    {
        $dirs = [LOGS, CACHE, TMP];
        foreach ($dirs as $dir) {
            if (!is_writable($dir)) {
                yield HealthStatus::warn(sprintf("Directory NOT writeable: %s", $dir));
            } else {
                yield HealthStatus::ok(sprintf("Directory writeable: %s", $dir));
            }
        }

        $dirs = [CONFIG, WWW_ROOT, DATA];
        foreach ($dirs as $dir) {
            if (!is_readable($dir)) {
                yield HealthStatus::warn(sprintf("Directory NOT readable: %s", $dir));
            } else {
                yield HealthStatus::ok(sprintf("Directory readable: %s", $dir));
            }
        }
    }
}
