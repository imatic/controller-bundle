<?php
namespace Imatic\Bundle\ControllerBundle\Resource\Config;

class ResourceConfig extends Config implements \ArrayAccess
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
