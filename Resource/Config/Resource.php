<?php
namespace Imatic\Bundle\ControllerBundle\Resource\Config;

class Resource extends Config
{
    /**
     * @var ResourceAction[]
     */
    protected $actions;

    /**
     * @var ResourceConfig
     */
    protected $config;

    /**
     * @var string
     */
    protected $name;

    public function __construct(array $actions = [], ResourceConfig $config = null, $name = null)
    {
        $this->actions = $actions;
        $this->config = $config;
        $this->name = $name;
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}