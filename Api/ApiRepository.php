<?php

namespace Imatic\Bundle\ControllerBundle\Api;

use Imatic\Bundle\ControllerBundle\Exception\ApiNotFoundException;

class ApiRepository
{
    /**
     * @var Api[]
     */
    private $apis;

    public function __construct()
    {
        $this->apis = [];
    }

    public function add($name, Api $api)
    {
        $this->apis[$name] = $api;
    }

    public function get($name)
    {
        if ($this->has($name)) {
            return $this->apis[$name];
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

    public function listing()
    {
        return $this->call('listing', func_get_args());
    }

    public function call($name, array $arguments)
    {
        $api = $this->get($name);

        return call_user_func_array([$api, $name], $arguments);
    }
}
