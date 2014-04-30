<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api;

use Imatic\Bundle\ControllerBundle\Exception\MissingApiRepositoryException;

/**
 * ApiTrait
 * Use ApiTrait as controller shortcut for API access
 */
trait ApiTrait
{
    /**
     * @var ApiRepository
     */
    private $apiRepository;

    /**
     * @param string $name
     * @param string $serviceId
     */
    public function addApi($name, $serviceId)
    {
        if (null == $this->apiRepository) {
            $this->apiRepository = new ApiRepository($this->container);
        }
        $this->apiRepository->add($name, $serviceId);
    }

    /**
     * @param $name
     * @param  array $arguments
     * @return Api
     * @throws MissingApiRepositoryException
     */
    protected function getApi($name, array $arguments = [])
    {
        if (!$this->apiRepository && isset($this->container)) {
            $this->apiRepository = $this->container->get('imatic_controller.api_repository');
        }

        if (!$this->apiRepository) {
            throw new MissingApiRepositoryException();
        }

        if ($arguments) {
            return $this->apiRepository->call($name, $arguments);
        } else {
            return $this->apiRepository->get($name);
        }
    }
}
