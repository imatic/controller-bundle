<?php
namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Resource\Config\Resource;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceAction;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ResourceController implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use ApiTrait;
    use SecurityTrait;

    /**
     * @return ResourceAction
     */
    protected function getActionConfig()
    {
        $container = $this->container;
        $request = $container->get('request_stack')->getCurrentRequest();

        $resourceName = $request->attributes->get('resource');
        $actionName = $request->attributes->get('action');

        $resource = $container->get('imatic_controller.resource.' . $resourceName);
        $action = $resource->getAction($actionName);

        $this->checkAuthorization($resource, $action);

        return $action;
    }

    /**
     * @return \Imatic\Bundle\ControllerBundle\Resource\Config\Resource
     */
    protected function getResourceConfig()
    {
        $container = $this->container;
        $request = $container->get('request_stack')->getCurrentRequest();

        $resourceName = $request->attributes->get('resource');

        return $container->get('imatic_controller.resource.' . $resourceName);
    }

    protected function checkAuthorization(Resource $resource, ResourceAction $action)
    {
        if (isset($resource->getConfig()['role'])) {
            $this->denyAccessUnlessGranted($resource->getConfig()['role']);
        }

        if (isset($action['role'])) {
            $this->denyAccessUnlessGranted($action['role']);
        }
    }
}
