<?php

namespace Imatic\Bundle\ControllerBundle\Generator;

class ResourceConfiguration
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Can not set config value');
    }

    public function offsetUnset($offset)
    {
        throw new \LogicException('Can not unset config value');
    }
}