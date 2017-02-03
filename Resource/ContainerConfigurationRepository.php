<?php

namespace Imatic\Bundle\ControllerBundle\Resource;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerConfigurationRepository implements ConfigurationRepositoryInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $resourceNames;

    public function __construct(ContainerInterface $container, array $resourceNames)
    {
        $this->container = $container;
        $this->resourceNames = array_combine($resourceNames, $resourceNames);
    }

    public function getResources()
    {
        return array_map(function ($resourceName) {
            return $this->initializeResource(
                $this->container->getParameter(static::formatParameterName($resourceName))
            );
        }, $this->resourceNames);
    }

    public function getResource($resourceName)
    {
        if (!isset($this->resourceNames[$resourceName])) {
            throw new \InvalidArgumentException(
                sprintf('Resource "%s" not found', $resourceName)
            );
        }

        return $this->initializeResource($this->container->getParameter(static::formatParameterName($resourceName)));
    }

    private function initializeResource($resource)
    {
        if (is_string($resource)) {
            $resource = unserialize($resource);
        }

        return $resource;
    }

    public static function formatParameterName($resourceName)
    {
        return sprintf('imatic_controller.resource.config.%s', $resourceName);
    }
}
