<?php
declare(strict_types=1);

namespace Banana\Plugin;

/**
 * @deprecated Will be removed when upgrading to CakePHP 4.0.0. Every plugin MUST use a plugin class by then
 */
class GenericPlugin extends BasePlugin
{
    public function __construct(array $config)
    {
        if (isset($config['name'])) {
            $this->name = $config['name'];
        }

        parent::__construct($config);
    }
}
