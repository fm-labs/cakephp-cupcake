<?php
declare(strict_types=1);

namespace Cupcake\Health;

/**
 * Class HealthStatus
 *
 * @package Cupcake\Health
 */
class HealthStatus
{
    public const UNKNOWN = 0;
    public const OK = 1;
    public const WARN = 2;
    public const CRIT = 3;

    /**
     * @var int
     */
    protected $_status;

    /**
     * @var string
     */
    protected $_msg;

    /**
     * @var array
     */
    protected $_log = [];

    /**
     * HealthStatus constructor.
     *
     * @param int $status Status code
     * @param string $msg Status message
     */
    public function __construct(int $status, string $msg = '')
    {
        $this->_status = $status;
        $this->_msg = $msg;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->_status;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->_msg;
    }

    /**
     * @param int $status Status code
     * @return $this
     */
    public function setStatus(int $status)
    {
        $this->_status = $status;

        return $this;
    }

    /**
     * @param string $msg Status message
     * @return $this
     */
    public function setMessage(string $msg)
    {
        $this->_msg = $msg;

        return $this;
    }

    /**
     * @param string $line Log line
     * @return $this
     */
    public function log(string $line)
    {
        $this->_log[] = $line;

        return $this;
    }

    /**
     * @param string $msg The status message
     * @return static
     */
    public static function unknown(string $msg = ''): self
    {
        $msg = $msg ?? __d('cupcake', 'Unknown health status');

        return new self(self::UNKNOWN, $msg);
    }

    /**
     * @param string $msg The status message
     * @return static
     */
    public static function ok(string $msg = ''): self
    {
        return new self(self::OK, $msg);
    }

    /**
     * @param string $msg The status message
     * @return static
     */
    public static function warn(string $msg = ''): self
    {
        return new self(self::WARN, $msg);
    }

    /**
     * @param string $msg The status message
     * @return static
     */
    public static function crit(string $msg = ''): self
    {
        return new self(self::CRIT, $msg);
    }
}
