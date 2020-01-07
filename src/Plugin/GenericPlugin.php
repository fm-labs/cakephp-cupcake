<?php
namespace Banana\Plugin;

class GenericPlugin extends BasePlugin
{
    public function __construct(array $config)
    {
        if (isset($config['name'])) {
            $this->_name = $config['name'];
        }

        parent::__construct($config);
    }
}
