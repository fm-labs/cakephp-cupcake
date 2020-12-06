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
    protected $_checks = [];

    /**
     * @var array
     */
    protected $_results = [];

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
            if (!($check instanceof HealthInterface)) {
                throw new \Exception('Invalid health check. Must implement HealthInterface.');
            }

            /** @var \Cupcake\Health\HealthInterface $check */
            $result = $check->getHealthStatus();
            $this->_results[$name] = $result;
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
     * @param \Cupcake\Health\HealthInterface|callable|array $check Check
     * @return $this
     */
    public function addCheck(string $name, $check)
    {
        if (is_array($check)) {
            $check = new HealthCheck($name, $check);
        }
        if (is_callable($check)) {
            $check = new HealthCheck($name, ['callback' => $check]);
        }
        //if (!($check instanceof HealthInterface)) {
        //    throw new \Exception('Invalid health check. Must implement HealthInterface.');
        //}

        $this->_checks[$name] = $check;

        return $this;
    }
}
