<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Api\GetApiTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ResourceController implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use GetApiTrait;

    /**
     * @return array
     */
    protected function getConfig()
    {
        $container = $this->container;
        $request = $container->get('request_stack')->getCurrentRequest();

        $resource = $request->attributes->get('_imatic_controller_resource');
        $action = $request->attributes->get('_imatic_controller_action');

        return $container
            ->get('imatic_controller.resource.config_repository')
            ->get($resource, $action);
    }
}
