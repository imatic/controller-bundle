<?php

namespace Imatic\Bundle\ControllerBundle\Routing\Loader;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class CrudLoader extends Loader
{
    private $name;

    private $loaded;

    private $config;

    public function __construct()
    {
        $this->name = 'imatic_crud';
        $this->loaded = false;
        $this->config = [];
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "' . $this->name . '" loader twice');
        }

        $routes = new RouteCollection();

        foreach ($this->config as $config) {
            $path = '/extra/{parameter}';
            $defaults = [
                '_controller' => 'AppBundle:Extra:extra',
                '_type' => 'imatic_directory_company',
            ];
            $requirements = [
                'parameter' => '\d+',
            ];
            $route = new Route($path, $defaults, $requirements);

            // add the new route to the route collection
            $routeName = 'extraRoute';
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