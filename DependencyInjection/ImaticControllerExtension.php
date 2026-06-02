<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\DependencyInjection;

use Imatic\Bundle\ControllerBundle\Resource\Config\Resource;
use Imatic\Bundle\ControllerBundle\Resource\ConfigurationProcessor;
use Imatic\Bundle\ControllerBundle\Resource\ConfigurationRepository;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class ImaticControllerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Merge configuration files
        $config = [];
        foreach ($configs as $subConfig) {
            $config = \array_replace_recursive($config, $subConfig);
        }

        // Process and validate configuration
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, [$config]);

        // Resources configuration
        $this->processResourcesConfiguration($config['resources_config'] ?? [], $config['resources'], $container);

        // Load services
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        $this->loadImportConfiguration($loader);
    }

    private function processResourcesConfiguration(array $resourcesConfig, array $resources, ContainerBuilder $container): void
    {
        $resourceConfigurationProcessor = new ConfigurationProcessor();
        $config = $resourceConfigurationProcessor->process($resourcesConfig, $resources);

        $definition = new Definition(ConfigurationRepository::class);
        foreach ($config as $resourceName => $resource) {
            $resourceDefinition = new Definition(Resource::class);
            $resourceDefinition->setFactory([Resource::class, 'fromSerialized']);
            $resourceDefinition->setArguments([\serialize($resource)]);
            $resourceDefinition->setPublic(true);
            $container->setDefinition('imatic_controller.resource.' . $resourceName, $resourceDefinition);

            $definition->addMethodCall('addResource', [$resourceName, $resourceDefinition]);
        }

        $container->setDefinition('imatic_controller.resources.resource_repository', $definition);
        $container->setAlias(ConfigurationRepository::class, 'imatic_controller.resources.resource_repository')->setPublic(true);
    }

    private function loadImportConfiguration(Loader\YamlFileLoader $loader): void
    {
        if (\class_exists('Imatic\Bundle\ImportExportBundle\ImaticImportExportBundle')) {
            $loader->load('import_export.yaml');
        }
    }
}
