<?php
declare(strict_types=1);

namespace Cupcake\Health;

/**
 * Class HealthManager
 *
 * @package Cupcake\Health
 */
class HealthManager
{
    /**
     * @var array
     */
    protected array $_checks = [];

    /**
     * @var array
     */
    protected array $_results = [];

    /**
     * HealthManager constructor.
     *
     * @param array $checks Initial set of checks
     * @throws \Exception
     */
    public function __construct(array $checks = [])
    {
        foreach ($checks as $name => $check) {
            $this->addCheck($name, $check);
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function check(): void
    {
        $this->_results = [];
        foreach ($this->_checks as $name => $check) {
            if ($check instanceof HealthCheckInterface) {
                $result = $check->getHealthStatus();
                $this->_results[$name][] = $result;
            } elseif ($check instanceof HealthCheckGeneratorInterface) {
                foreach ($check->getHealthStatus() as $result) {
                    $this->_results[$name][] = $result;
                }
            } else {
                //throw new \Exception('Invalid health check. Must implement HealthCheckInterface or HealthCheckGeneratorInterface.');
                $this->_results[$name][] = HealthStatus::crit('Invalid health check. Must implement HealthCheckInterface or HealthCheckGeneratorInterface.');
            }
        }
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->_results;
    }

    /**
     * @param string $name Check name
     * @param \Cupcake\Health\HealthCheckInterface|callable|array $check Check
     * @return $this
     */
    public function addCheck(string $name, $check): static
    {
        if (is_array($check)) {
            if (isset($check['generator'])) {
                $check = new HealthCheckGenerator($check['generator']);
            }
            elseif (isset($check['callback'])) {
                $check = new HealthCheck($check['callback']);
            }
            else {
                throw new \RuntimeException("Invalid health check options");
            }
        }
        if (is_callable($check)) {
            $check = new HealthCheck($check);
        }

        $this->_checks[$name] = $check;

        return $this;
    }
}
