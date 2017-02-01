<?php

namespace Imatic\Bundle\ControllerBundle\Routing\Loader;

use Imatic\Bundle\ControllerBundle\Controller\Resource\ResourceConfigurationRepository;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ResourceLoader extends Loader
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $loaded;

    /**
     * @var ResourceConfigurationRepository
     */
    private $repository;

    public function __construct(ResourceConfigurationRepository $resourceConfigurationRepository)
    {
        $this->name = 'imatic_resource';
        $this->loaded = false;
        $this->repository = $resourceConfigurationRepository;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "' . $this->name . '" loader twice');
        }

        $routes = new RouteCollection();

        foreach ($this->repository->getActions() as $action) {
            $path = $action['route']['path'];
            $defaults = [
                '_controller' => $action['controller'],
                '_imatic_controller_resource' => $action['resource'],
                '_imatic_controller_action' => $action['action'],
            ];

            $requirements = !empty($action['route']['requirements']) ? $action['route']['requirements'] : [];
            $options = [];
            $host = null;
            $schemes = null;
            $methods = $action['route']['methods'];

            $route = new Route($path, $defaults, $requirements, $options, $host, $schemes, $methods);

            $routeName = $action['route']['name'];

            $routes->add($routeName, $route);
        }

        $this->loaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return $this->name === $type;
    }
}