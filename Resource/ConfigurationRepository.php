<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Resource;

use Imatic\Bundle\ControllerBundle\Resource\Config\Resource;

class ConfigurationRepository
{
    /**
     * @var resource[]
     */
    private $resources;

    public function __construct()
    {
        $this->resources = [];
    }

    public function addResource($resourceName, Resource $resource)
    {
        $this->resources[$resourceName] = $resource;
    }

    /**
     * @return resource[]
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param string $resourceName
     *
     * @return resource
     */
    public function getResource($resourceName)
    {
        if (!isset($this->resources[$resourceName])) {
            throw new \InvalidArgumentException(
                \sprintf('Resource "%s" not found', $resourceName)
            );
        }

        return $this->resources[$resourceName];
    }

    /**
     * @return array
     */
    public function getResourceNames()
    {
        return \array_keys($this->resources);
    }
}
