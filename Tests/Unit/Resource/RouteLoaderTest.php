<?php
namespace Imatic\Bundle\ControllerBundle\Test\Resource;

use Imatic\Bundle\ControllerBundle\Resource\Config\Resource;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceAction;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceConfig;
use Imatic\Bundle\ControllerBundle\Resource\ConfigurationRepository;
use Imatic\Bundle\ControllerBundle\Resource\RouteLoader;
use PHPUnit\Framework\TestCase;

class RouteLoaderTest extends TestCase
{
    public function testEmptyRouteLoader()
    {
        $repository = new ConfigurationRepository();

        $loader = new RouteLoader($repository);
        $routes = $loader->load(null, null);

        $this->assertCount(0, $routes);
    }

    public function testRouteLoader()
    {
        $repository = new ConfigurationRepository();
        $repository->addResource('app_user', new Resource(
            [new ResourceAction(
                [
                    'route' => ['path' => '/list', 'name' => 'user_list', 'methods' => ['get']],
                    'controller' => 'UserController',
                    'name' => 'list',
                ]
            )],
            new ResourceConfig([]),
            'app_user'
        ));

        $loader = new RouteLoader($repository);
        $routes = $loader->load(null, null);

        $this->assertCount(1, $routes);

        $route = $routes->get('user_list');
        $this->assertEquals('/list', $route->getPath());
        $this->assertEquals(['GET'], $route->getMethods());
        $this->assertEquals(
            ['_controller' => 'UserController', 'resource' => 'app_user', 'action' => 'list'],
            $route->getDefaults()
        );
    }
}
