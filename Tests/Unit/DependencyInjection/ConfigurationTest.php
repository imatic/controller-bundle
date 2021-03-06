<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\DependencyInjection;

use Imatic\Bundle\ControllerBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class ConfigurationTest extends TestCase
{
    public function testResourcesConfigConfiguration()
    {
        $config = Yaml::parse(\file_get_contents(__DIR__ . '/../../../Resources/config/config.yml'));

        $configuration = new Configuration();

        $processor = new Processor();
        $processor->processConfiguration($configuration, [$config['imatic_controller']]);

        $this->assertIsArray($config);
    }

    public function testResourcesConfiguration()
    {
        $config = Yaml::parse(\file_get_contents(__DIR__ . '/../../Fixtures/TestProject/config/resources/user.yml'));

        $configuration = new Configuration();

        $processor = new Processor();
        $processor->processConfiguration($configuration, [$config['imatic_controller']]);

        $this->assertIsArray($config);
    }
}
