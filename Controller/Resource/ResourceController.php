<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Resource\Config\Resource;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceAction;
use Imatic\Bundle\ControllerBundle\Resource\ConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResourceController extends AbstractController
{
    use ApiTrait;

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            ConfigurationRepository::class => ConfigurationRepository::class,
        ]);
    }

    protected function getActionConfig(): ResourceAction
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        $resourceName = $request->attributes->get('resource');
        $actionName = $request->attributes->get('action');

        $resource = $this->container->get(ConfigurationRepository::class)->getResource($resourceName);
        $action = $resource->getAction($actionName);

        $this->checkAuthorization($resource, $action);

        return $action;
    }

    protected function getResourceConfig(): Resource
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $resourceName = $request->attributes->get('resource');

        return $this->container->get(ConfigurationRepository::class)->getResource($resourceName);
    }

    protected function checkAuthorization(Resource $resource, ResourceAction $action): void
    {
        if (isset($resource->getConfig()['role'])) {
            $this->denyAccessUnlessGranted($resource->getConfig()['role']);
        }

        if (isset($action['role'])) {
            $this->denyAccessUnlessGranted($action['role']);
        }
    }
}
