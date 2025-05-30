<?php
declare(strict_types=1);

namespace Cupcake\Health;

use Closure;
use Generator;

/**
 * Class HealthCheck
 *
 * @package Cupcake\Health
 */
class HealthCheckGenerator implements HealthCheckGeneratorInterface
{
    protected $callback;

    /**
     * HealthCheck constructor.
     *
     * @param \Closure|\Generator|callable $callback
     */
    public function __construct(callable|Generator|Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @inheritDoc
     */
    public function getHealthStatus(): Generator
    {
        if (!$this->callback || !is_callable($this->callback)) {
            yield HealthStatus::crit('ERROR: HEALTH STATUS CHECK FAILED: Invalid callback');
        }

        $i = 0;
        foreach (call_user_func($this->callback) as $result) {
            $i++;
            yield $result;
        }

        if ($i === 0) {
            yield HealthStatus::ok();
        }
    }
}
