<?php

namespace Imatic\Bundle\ControllerBundle\Resource;

use Imatic\Bundle\ControllerBundle\Resource\Config\Resource;

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
