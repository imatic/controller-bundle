<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Resource;

use Imatic\Bundle\ControllerBundle\Resource\Config\Resource;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceAction;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceConfig;
use Imatic\Bundle\ControllerBundle\Resource\ConfigurationRepository;
use PHPUnit\Framework\TestCase;

class ConfigurationRepositoryTest extends TestCase
{
    public function testGetResources()
    {
        $repository = new ConfigurationRepository();
        $repository->addResource('key1', new Resource([new ResourceAction(['list' => []])], new ResourceConfig(['entity' => 'E1']), 'r1'));
        $repository->addResource('key2', new Resource([new ResourceAction(['show' => []])], new ResourceConfig(['entity' => 'E2']), 'r2'));

        $this->assertCount(2, $repository->getResources());
        $this->assertInstanceOf(Resource::class, $repository->getResources()['key1']);
        $this->assertInstanceOf(Resource::class, $repository->getResources()['key2']);
        $this->assertEquals('E1', $repository->getResources()['key1']->getConfig()->entity);
        $this->assertEquals('E2', $repository->getResources()['key2']->getConfig()->entity);
    }

    public function testGetResource()
    {
        $repository = new ConfigurationRepository();
        $repository->addResource('key1', new Resource([new ResourceAction(['list' => []])], new ResourceConfig(['entity' => 'E1']), 'r1'));

        $this->assertInstanceOf(Resource::class, $repository->getResource('key1'));
        $this->assertEquals('E1', $repository->getResources()['key1']->getConfig()->entity);
    }

    public function testGetResourceNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Resource "key2" not found');

        $repository = new ConfigurationRepository();
        $repository->addResource('key1', new Resource([new ResourceAction(['list' => []])], new ResourceConfig(['entity' => 'E1']), 'r1'));

        $this->assertInstanceOf(Resource::class, $repository->getResource('key2'));
    }
}
