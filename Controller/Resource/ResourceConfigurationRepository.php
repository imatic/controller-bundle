<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

class ResourceConfigurationRepository
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getAction($resource, $action)
    {
        if (!isset($this->config[$resource]['actions'][$action])) {
            throw new \InvalidArgumentException(
                sprintf('Resource:action "%s:%s" not found', $resource, $action)
            );
        }

        return $this->config[$resource]['actions'][$action];
    }

    /**
     * @return array
     */
    public function getResource($resource)
    {
        if (!isset($this->config[$resource])) {
            throw new \InvalidArgumentException(
                sprintf('Resource "%s" not found', $resource)
            );
        }

        return $this->config[$resource];
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return array_reduce($this->config, function (array $reduced, array $resource) {
            return array_reduce($resource['actions'], function (array $reduced, array $action) {
                $reduced[] = $action;

                return $reduced;
            }, $reduced);
        }, []);
    }

    /**
     * @return array
     */
    public function getResourceNames()
    {
        return array_reduce($this->config, function (array $reduced, array $resource) {
            $reduced[] = $resource['resource'];

            return $reduced;
        }, []);
    }
}
