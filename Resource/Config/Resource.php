<?php
namespace Imatic\Bundle\ControllerBundle\Resource\Config;

class Resource extends Config
{
    /**
     * @var ResourceAction[]
     */
    private $actions;

    /**
     * @var ResourceConfig
     */
    private $config;

    public function __construct(array $actions, ResourceConfig $config)
    {
        $this->actions = $actions;
        $this->config = $config;
    }

    /**
     * @return ResourceAction[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return ResourceAction
     */
    public function getAction($name)
    {
        return $this->actions[$name];
    }

    /**
     * @return ResourceConfig
     */
    public function getConfig()
    {
        return $this->config;
    }
}