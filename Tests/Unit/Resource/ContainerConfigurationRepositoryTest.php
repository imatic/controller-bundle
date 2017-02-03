<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Resource;

use Imatic\Bundle\ControllerBundle\Resource\Config\Resource;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceAction;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceConfig;
use Imatic\Bundle\ControllerBundle\Resource\ContainerConfigurationRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerConfigurationRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ContainerInterface $container
     */
    public function getContainerMock()
    {
        return $this
            ->getMockBuilder(ContainerInterface::class)
            ->getMock();

    }

    public function testGetResources()
    {
        $container = $this->getContainerMock();
        $container
            ->expects($this->any())
            ->method('getParameter')
            ->will($this->returnCallback(function ($key) {
                $key1 = ContainerConfigurationRepository::formatParameterName('key1');
                $key2 = ContainerConfigurationRepository::formatParameterName('key2');
                $map = [
                    $key1 => serialize(new Resource([new ResourceAction(['list' => []])], new ResourceConfig(['entity' => 'E1']), 'r1')),
                    $key2 => new Resource([new ResourceAction(['show' => []])], new ResourceConfig(['entity' => 'E2']), 'r2'),
                ];

                return $map[$key];
            }));

        $repository = new ContainerConfigurationRepository($container, ['key1', 'key2']);

        $this->assertCount(2, $repository->getResources());
        $this->assertInstanceOf(Resource::class, $repository->getResources()['key1']);
        $this->assertInstanceOf(Resource::class, $repository->getResources()['key2']);
        $this->assertEquals('E1', $repository->getResources()['key1']->getConfig()->entity);
        $this->assertEquals('E2', $repository->getResources()['key2']->getConfig()->entity);
    }

    public function testGetResource()
    {
        $container = $this->getContainerMock();
        $container
            ->expects($this->any())
            ->method('getParameter')
            ->will($this->returnCallback(function ($key) {
                $key1 = ContainerConfigurationRepository::formatParameterName('key1');
                $map = [
                    $key1 => serialize(new Resource([new ResourceAction(['list' => []])], new ResourceConfig(['entity' => 'E1']), 'r1')),
                ];

                return $map[$key];
            }));

        $repository = new ContainerConfigurationRepository($container, ['key1']);

        $this->assertInstanceOf(Resource::class, $repository->getResource('key1'));
        $this->assertEquals('E1', $repository->getResources()['key1']->getConfig()->entity);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Resource "key2" not found
     */
    public function testGetResourceNotExists()
    {
        $container = $this->getContainerMock();
        $container
            ->expects($this->any())
            ->method('getParameter')
            ->will($this->returnCallback(function ($key) {
                $key1 = ContainerConfigurationRepository::formatParameterName('key1');
                $map = [
                    $key1 => serialize(new Resource([new ResourceAction(['list' => []])], new ResourceConfig(['entity' => 'E1']), 'r1')),
                ];

                return $map[$key];
            }));

        $repository = new ContainerConfigurationRepository($container, ['key1']);

        $this->assertInstanceOf(Resource::class, $repository->getResource('key2'));
    }
}
