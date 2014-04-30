<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api;

use Imatic\Bundle\ControllerBundle\Exception\ApiNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ApiRepository
{
    /**
     * @var string[]
     */
    private $apis;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->apis = [];
    }

    public function add($name, $serviceId)
    {
        $this->apis[$name] = $serviceId;
    }

    public function get($name)
    {
        if ($this->has($name)) {
            return $this->container->get($this->apis[$name]);
        }
        throw new ApiNotFoundException($name);
    }

    public function has($name)
    {
        return array_key_exists($name, $this->apis);
    }

    public function __call($name, array $arguments)
    {
        return $this->call($name, $arguments);
    }

    public function call($name, array $arguments)
    {
        $api = $this->get($name);

        return call_user_func_array([$api, $name], $arguments);
    }
}
