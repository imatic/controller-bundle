<?php

namespace Imatic\Bundle\ControllerBundle\Resource;

interface ConfigurationRepositoryInterface
{
    /**
     * @param string $name
     * @return Resource
     */
    public function getResource($name);

    /**
     * @return Resource[]
     */
    public function getResources();
}
