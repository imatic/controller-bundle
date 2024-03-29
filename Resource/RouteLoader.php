<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Resource;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader extends Loader
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $loaded;

    /**
     * @var ConfigurationRepository
     */
    private $repository;

    public function __construct(ConfigurationRepository $configurationRepository)
    {
        $this->name = 'imatic_resource';
        $this->loaded = false;
        $this->repository = $configurationRepository;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "' . $this->name . '" loader twice');
        }

        $routes = new RouteCollection();

        /** @var resource $resourceItem */
        foreach ($this->repository->getResources() as $resourceItem) {
            foreach ($resourceItem->getActions() as $action) {
                if (!empty($action['target'])) {
                    continue;
                }

                $path = $action['route']['path'];
                $defaults = \array_replace(
                    !empty($action['route']['defaults']) ? $action['route']['defaults'] : [],
                    [
                        '_controller' => $action['controller'],
                        'resource' => $resourceItem->getName(),
                        'action' => $action['name'],
                    ]
                );

                $requirements = !empty($action['route']['requirements']) ? $action['route']['requirements'] : [];
                $options = [];
                $host = null;
                $schemes = '';
                $methods = $action['route']['methods'];

                $route = new Route($path, $defaults, $requirements, $options, $host, $schemes, $methods);

                $routeName = $action['route']['name'];

                $routes->add($routeName, $route);
            }
        }

        $this->loaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return $this->name === $type;
    }
}
