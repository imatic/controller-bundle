<?php
namespace Imatic\Bundle\ControllerBundle\Resource\Config;

class ResourceAction extends Config implements \ArrayAccess
{
    use AccessTrait;

    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
}