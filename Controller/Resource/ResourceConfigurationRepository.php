<?php

namespace Imatic\Bundle\ControllerBundle\Generator;

class ResourceConfigurationRepository
{
    private $config;

    public function setAll(array $config)
    {
        $this->config = $config;
    }

    public function get($key)
    {
        if (!isset($this->config[$key])) {
            throw new \InvalidArgumentException(sprintf('Invalid config key "%s"', $key));
        }

        return $this->config[$key];
    }
}
