<?php
declare(strict_types=1);

namespace Cupcake\Health;

/**
 * Class HealthCheck
 *
 * @package Cupcake\Health
 */
class HealthCheck implements HealthInterface
{
    protected $label;
    protected $callback;

    /**
     * HealthCheck constructor.
     *
     * @param string $name Check name
     * @param array $options Check options
     */
    public function __construct(string $name, array $options = [])
    {
        foreach (['label', 'callback'] as $key) {
            $this->{$key} = $options[$key] ?? null;
        }
    }

    /**
     * @inheritDoc
     */
    public function getHealthStatus(): HealthStatus
    {
        if (!$this->callback || !is_callable($this->callback)) {
            return HealthStatus::crit('ERROR: HEALTH STATUS CHECK FAILED: Invalid callback');
        }

        $status = HealthStatus::unknown();
        $result = call_user_func($this->callback, $status);
        if ($result instanceof HealthStatus) {
            return $result;
        }

        if ($result === true) {
            return HealthStatus::ok();
        }
        if ($result === false || is_string($result)) {
            return HealthStatus::crit((string)$result);
        }

        return HealthStatus::warn();
    }
}
