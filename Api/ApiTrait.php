<?php

namespace Imatic\Bundle\ControllerBundle\Api;

use Imatic\Bundle\ControllerBundle\Exception\MissingApiRepositoryException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ApiTrait
 * Use ApiTrait as controller shortcut for API access
 */
trait ApiTrait
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ApiRepository
     */
    private $apiRepository;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return ShowApi
     */
    public function show()
    {
        return $this->getApi('show', func_get_args());
    }

    /**
     * @return ListApi
     */
    public function listing()
    {
        return $this->getApi('list', func_get_args());
    }

    /**
     * @param string $name
     * @param Api    $api
     */
    public function addApi($name, Api $api)
    {
        if (null == $this->apiRepository) {
            $this->apiRepository = new ApiRepository();
        }
        $this->apiRepository->add($name, $api);
    }

    /**
     * @param $name
     * @param  array           $arguments
     * @return Api
     * @throws \LogicException
     */
    public function getApi($name, array $arguments = [])
    {
        if (!$this->apiRepository && $this->container) {
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
